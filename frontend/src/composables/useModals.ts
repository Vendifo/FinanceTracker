import { ref } from 'vue'
import type { User } from '@/api/users'

export function useModals() {
  const editUserModal = ref<User | null>(null)
  const deleteUserModal = ref<User | null>(null)
  const changePasswordModal = ref<User | null>(null)

  const openEdit = (user: User) => editUserModal.value = { ...user }
  const closeEdit = () => editUserModal.value = null

  const openDelete = (user: User) => deleteUserModal.value = user
  const closeDelete = () => deleteUserModal.value = null

  const openChangePassword = (user: User) => changePasswordModal.value = user
  const closeChangePassword = () => changePasswordModal.value = null

  return { editUserModal, deleteUserModal, changePasswordModal, openEdit, closeEdit, openDelete, closeDelete, openChangePassword, closeChangePassword }
}
