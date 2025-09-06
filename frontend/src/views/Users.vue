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

      <UsersTable :users="users" :roles="roles" :loading="loading" :error="error" @edit="openEdit" @delete="openDelete"
        @change-password="openChangePassword" />

      <EditUserModal v-if="editUserModal" :user="editUserModal" :roles="roles" @close="closeEdit" @save="saveUser" />

      <ChangePasswordModal v-if="changePasswordModal" :user="changePasswordModal" @close="closeChangePassword"
        @save="handleChangePassword" />

      <DeleteUserModal v-if="deleteUserModal" :user="deleteUserModal" @close="closeDelete" @confirm="removeUser" />

      <AddUserModal v-if="addUserModal" @close="closeAddUser" @save="createUser" />
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Header from '@/components/Header.vue'
import UsersTable from '@/components/users/UsersTable.vue'
import EditUserModal from '@/components/users/EditUserModal.vue'
import DeleteUserModal from '@/components/users/DeleteUserModal.vue'
import ChangePasswordModal from '@/components/users/ChangePasswordModal.vue'
import AddUserModal from '@/components/users/AddUserModal.vue'
import { useUsers } from '@/composables/useUsers'
import { useModals } from '@/composables/useModals'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'

// --- store и composables ---
const userStore = useUserStore()
const { users, roles, loading, error, saveUser, removeUser, updatePassword } = useUsers()
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

// --- модалка добавления ---
const addUserModal = ref(false)
const openAddUser = () => addUserModal.value = true
const closeAddUser = () => addUserModal.value = false

// --- создание нового пользователя ---
const createUser = async (payload: {
  name: string
  email: string
  password: string
  password_confirmation: string
}) => {
  try {
    const userStore = useUserStore()
    const res = await api.post('/users', payload, {
      headers: { Authorization: `Bearer ${userStore.token}` }
    })
    users.value.push(res.data.data)
    alert('Пользователь создан')
    closeAddUser()
  } catch (err: any) {
    alert(err.response?.data?.message || err.message)
  }
}

// --- смена пароля ---
const handleChangePassword = async (payload: {
  current_password?: string
  new_password: string
  new_password_confirmation: string
}) => {
  if (!changePasswordModal.value) return
  try {
    await updatePassword(changePasswordModal.value.id, payload)
    alert('Пароль успешно изменён')
    closeChangePassword()
  } catch (err: any) {
    alert(err.response?.data?.message || err.message)
  }
}
</script>
