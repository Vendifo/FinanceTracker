<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 p-6 w-full max-w-7xl mx-auto space-y-8">
      <!-- Пользователи -->
      <section>
        <div class="flex justify-between items-center mb-4">
          <h1 class="text-2xl font-bold">Пользователи</h1>
          <button
            @click="openAddUser"
            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition"
          >
            Добавить пользователя
          </button>
        </div>

        <UsersTable
          :users="users"
          :roles="roles"
          :loading="loading"
          :error="error"
          @edit="openEdit"
          @delete="openDelete"
          @change-password="openChangePassword"
          @manage-offices="openManageOffices"
        />
      </section>

      <!-- Сетка с таблицами -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <RolesTable />
        <ArticlesTable />
        <OfficesTable />
        <div
          class="bg-white rounded-lg shadow p-6 flex items-center justify-center text-gray-400"
        >
          Дополнительный блок
        </div>
      </section>
    </main>

    <!-- Модалки -->
    <EditUserModal
      v-if="editUserModal"
      :key="editUserModal.id"
      :user="editUserModal"
      :roles="roles"
      @close="closeEdit"
      @save="handleSaveUser"
    />

    <AddUserModal
      v-if="addUserModal"
      :roles="roles"
      @close="closeAddUser"
      @save="createUser"
    />

    <DeleteUserModal
      v-if="deleteUserModal"
      :user="deleteUserModal"
      @close="closeDelete"
      @confirm="handleConfirmDelete"
    />

    <ChangePasswordModal
      v-if="changePasswordModal"
      :user="changePasswordModal"
      @close="closeChangePassword"
      @save="handleChangePassword"
    />

    <ManageOfficesModal
  v-if="manageOfficesModal"
  :user="selectedUser"
  :offices="offices"
  v-model="userOffices"
  @close="manageOfficesModal = false"
  @save="saveUserOffices"
/>


  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Header from '@/components/Header.vue'
import UsersTable from '@/components/users/UsersTable.vue'
import EditUserModal from '@/components/users/EditUserModal.vue'
import AddUserModal from '@/components/users/AddUserModal.vue'
import DeleteUserModal from '@/components/users/DeleteUserModal.vue'
import ChangePasswordModal from '@/components/users/ChangePasswordModal.vue'
import RolesTable from '@/components/users/Roles.vue'
import ArticlesTable from '@/components/users/ArticlesTable.vue'
import OfficesTable from '@/components/users/OfficesTable.vue'
import ManageOfficesModal from '@/components/users/ManageOfficesModal.vue'

import type { User } from '@/api/users'

import { useUsers } from '@/composables/useUsers'
import { useModals } from '@/composables/useModals'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'
import { useAlert } from '@/composables/useAlert'

const userStore = useUserStore()
const { users, roles, loading, error, saveUser, removeUser, updatePassword } =
  useUsers()

// Модалки пользователей
const {
  editUserModal,
  deleteUserModal,
  changePasswordModal,
  openEdit,
  closeEdit,
  openDelete,
  closeDelete,
  openChangePassword,
  closeChangePassword,
} = useModals()

const addUserModal = ref(false)
const openAddUser = () => (addUserModal.value = true)
const closeAddUser = () => (addUserModal.value = false)

const { showAlert } = useAlert()

// ----------------------
// Логика для офисов
// ----------------------
const manageOfficesModal = ref(false)
const selectedUser = ref<User | null>(null)
const offices = ref<any[]>([])
const userOffices = ref<number[]>([])

const openManageOffices = async (user: User) => {
  selectedUser.value = user
  manageOfficesModal.value = true

  try {
    // Получаем все офисы
    const resOffices = await api.get('/offices', {
      headers: { Authorization: `Bearer ${userStore.token}` },
    })
    offices.value = resOffices.data.data || resOffices.data

    // Получаем офисы пользователя
    const resUserOffices = await api.get(`/users/${user.id}/offices`, {
      headers: { Authorization: `Bearer ${userStore.token}` },
    })

    // Важно: из data достаем id каждого офиса
    userOffices.value = resUserOffices.data.data?.map((o: any) => o.id) || []
  } catch (err: any) {
    showAlert({
      type: 'error',
      title: 'Ошибка',
      message: 'Не удалось загрузить офисы пользователя',
    })
  }
}


const saveUserOffices = async () => {
  if (!selectedUser.value) return
  try {
    await api.put(
      `/users/${selectedUser.value.id}/offices`,
      { offices: userOffices.value },
      { headers: { Authorization: `Bearer ${userStore.token}` } }
    )
    showAlert({
      type: 'success',
      title: 'Успех',
      message: 'Офисы пользователя обновлены',
    })
    manageOfficesModal.value = false
  } catch (err: any) {
    showAlert({
      type: 'error',
      title: 'Ошибка',
      message: err.response?.data?.message || 'Не удалось обновить офисы',
    })
  }
}

// ----------------------
// CRUD пользователи
// ----------------------
const createUser = async (payload: {
  name: string
  email: string
  password: string
  password_confirmation: string
}) => {
  try {
    const res = await api.post('/users', payload, {
      headers: { Authorization: `Bearer ${userStore.token}` },
    })
    users.value.push(res.data.data)

    showAlert({
      type: 'success',
      title: 'Успех',
      message: 'Пользователь успешно создан',
    })
    closeAddUser()
  } catch (err: any) {
    showAlert({
      type: 'error',
      title: 'Ошибка',
      message: err.response?.data?.message || err.message,
    })
  }
}

const handleConfirmDelete = async (userId: number) => {
  await removeUser(userId)
  closeDelete()
}

const handleSaveUser = async (user: User) => {
  await saveUser(user)
  closeEdit()
}

const handleChangePassword = async (payload: {
  current_password?: string
  new_password: string
  new_password_confirmation: string
}) => {
  if (!changePasswordModal.value) return
  try {
    await updatePassword(changePasswordModal.value.id, payload)
    showAlert({
      type: 'success',
      title: 'Успех',
      message: 'Пароль успешно изменён',
    })
    closeChangePassword()
  } catch (err: any) {
    showAlert({
      type: 'error',
      title: 'Ошибка',
      message: err.response?.data?.message || err.message,
    })
  }
}
</script>
