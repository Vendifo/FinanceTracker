import { ref, onMounted } from 'vue'
import { fetchUsers, updateUser, assignRole, deleteUser, changePassword } from '@/api/users'
import type { User, Role } from '@/api/users'
import { useUserStore } from '@/stores/userStore'
import { useRoles } from '@/composables/useRoles'
import { useAlert } from '@/composables/useAlert'

const { showAlert } = useAlert()
export function useUsers() {
  const userStore = useUserStore()
  const users = ref<User[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const { roles, loadRoles } = useRoles()

  const loadUsers = async () => {
    loading.value = true
    error.value = null
    try {
      const usersRes = await fetchUsers(userStore.token)
      users.value = usersRes.data.data

      if (!roles.value.length) await loadRoles()
    } catch (err: any) {
      error.value = err.response?.data?.message || err.message || 'Ошибка при загрузке'
      showAlert({ type: 'error', title: 'Ошибка',  message: error.value ?? 'Неизвестная ошибка' }) // уведомление об ошибке
    } finally {
      loading.value = false
    }
  }

  const saveUser = async (user: User) => {
    try {
      await updateUser(user, userStore.token)
      const index = users.value.findIndex(u => u.id === user.id)
      if (index !== -1) {
        users.value[index] = { ...user }
      }
      showAlert({ type: 'success', title: 'Успех', message: 'Пользователь успешно обновлён' }) // уведомление об успехе
    } catch (err: any) {
      showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || 'Не удалось обновить пользователя' })
    }
  }

  const removeUser = async (userId: number) => {
    try {
      await deleteUser(userId, userStore.token)
      users.value = users.value.filter(u => u.id !== userId)
      showAlert({ type: 'success', title: 'Успех', message: 'Пользователь удалён' })
    } catch (err: any) {
      showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || 'Не удалось удалить пользователя' })
    }
  }

  const updatePassword = async (userId: number, payload: any) => {
    try {
      await changePassword(userId, payload, userStore.token)
      showAlert({ type: 'success', title: 'Успех', message: 'Пароль успешно изменён' })
    } catch (err: any) {
      showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || 'Не удалось изменить пароль' })
    }
  }

  onMounted(loadUsers)

  return { users, roles, loading, error, loadUsers, saveUser, removeUser, updatePassword }
}
