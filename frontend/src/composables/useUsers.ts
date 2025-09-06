import { ref, onMounted } from 'vue'
import { fetchUsers, updateUser, assignRole, deleteUser, changePassword } from '@/api/users'
import type { User, Role } from '@/api/users'

import { useUserStore } from '@/stores/userStore'
import api from '@/axios' // для получения ролей, так как fetchRoles нет

export function useUsers() {
  const userStore = useUserStore()
  const users = ref<User[]>([])
  const roles = ref<Role[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const load = async () => {
    loading.value = true
    error.value = null
    try {
      const [usersRes, rolesRes] = await Promise.all([
        fetchUsers(userStore.token),
        api.get<{ data: Role[] }>('/roles', { headers: { Authorization: `Bearer ${userStore.token}` } })
      ])
      users.value = usersRes.data.data
      roles.value = rolesRes.data.data
    } catch (err: any) {
      error.value = err.response?.data?.message || err.message || 'Ошибка при загрузке'
    } finally {
      loading.value = false
    }
  }

  const saveUser = async (user: User, roleId?: number) => {
    await updateUser(user, userStore.token)
    if (roleId) await assignRole(user.id, roleId, userStore.token)
    const index = users.value.findIndex(u => u.id === user.id)
    if (index !== -1) users.value[index] = { ...user, role: roles.value.find(r => r.id === roleId) }
  }

  const removeUser = async (userId: number) => {
    await deleteUser(userId, userStore.token)
    users.value = users.value.filter(u => u.id !== userId)
  }

  const updatePassword = async (userId: number, payload: any) => {
    await changePassword(userId, payload, userStore.token)
  }

  onMounted(load)

  return { users, roles, loading, error, load, saveUser, removeUser, updatePassword }
}
