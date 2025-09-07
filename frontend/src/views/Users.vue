<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 flex flex-col gap-6 p-6 w-full max-w-6xl mx-auto">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Пользователи</h1>
        <button @click="openAddUser" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
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
      />

      <EditUserModal
  v-if="editUserModal"
  :key="editUserModal.id"
  :user="editUserModal"
  :roles="roles"
  @close="closeEdit"
  @save="saveUser"
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
        @confirm="removeUser"
      />

      <ChangePasswordModal
        v-if="changePasswordModal"
        :user="changePasswordModal"
        @close="closeChangePassword"
        @save="handleChangePassword"
      />

      <RolesTable /> <!-- Вставляем таблицу ролей -->
    </main>
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

import { useUsers } from '@/composables/useUsers'
import { useModals } from '@/composables/useModals'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()
const { users, roles, loading, error, saveUser, removeUser, updatePassword } = useUsers()

// модалки пользователей
const {
  editUserModal,
  deleteUserModal,
  changePasswordModal,
  openEdit,
  closeEdit,
  openDelete,
  closeDelete,
  openChangePassword,
  closeChangePassword
} = useModals()

const addUserModal = ref(false)
const openAddUser = () => addUserModal.value = true
const closeAddUser = () => addUserModal.value = false

import { useAlert } from '@/composables/useAlert'

const { showAlert } = useAlert()
const createUser = async (payload: { name: string; email: string; password: string; password_confirmation: string }) => {
  try {
    const res = await api.post('/users', payload, {
      headers: { Authorization: `Bearer ${userStore.token}` }
    })
    users.value.push(res.data.data)

    showAlert({
      type: 'success',
      title: 'Успех',
      message: 'Пользователь успешно создан'
    })

    closeAddUser()
  } catch (err: any) {
    showAlert({
      type: 'error',
      title: 'Ошибка',
      message: err.response?.data?.message || err.message
    })
  }
}

const handleChangePassword = async (payload: { current_password?: string; new_password: string; new_password_confirmation: string }) => {
  if (!changePasswordModal.value) return
  try {
    await updatePassword(changePasswordModal.value.id, payload)

    showAlert({
      type: 'success',
      title: 'Успех',
      message: 'Пароль успешно изменён'
    })

    closeChangePassword()
  } catch (err: any) {
    showAlert({
      type: 'error',
      title: 'Ошибка',
      message: err.response?.data?.message || err.message
    })
  }
}

</script>
