import { ref } from 'vue'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'
import { useAlert } from '@/composables/useAlert'

const { showAlert } = useAlert()
let cachedRoles: any[] | null = null // кеш для ролей

export function useRoles() {
  const roles = ref<any[]>(cachedRoles || [])
  const loading = ref(false)
  const error = ref<string | null>(null)
  const userStore = useUserStore()

  const loadRoles = async () => {
    if (cachedRoles) {
      roles.value = cachedRoles
      return
    }

    loading.value = true
    error.value = null
    try {
      const res = await api.get('/roles', {
        headers: { Authorization: `Bearer ${userStore.token}` }
      })
      roles.value = res.data.data || res.data
      cachedRoles = roles.value
    } catch (err: any) {
      error.value = err.response?.data?.message || err.message || 'Ошибка при загрузке ролей'
      showAlert({ type: 'error', title: 'Ошибка', message: error.value || 'Ошибка при загрузке ролей' })
    } finally {
      loading.value = false
    }
  }

  const createRole = async (payload: { name: string }) => {
    try {
      const res = await api.post('/roles', payload, {
        headers: { Authorization: `Bearer ${userStore.token}` }
      })
      const newRole = res.data.data || res.data
      roles.value.push(newRole)
      cachedRoles = roles.value
      showAlert({ type: 'success', title: 'Успех', message: `Роль "${payload.name}" создана` })
    } catch (err: any) {
      showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || 'Не удалось создать роль' })
    }
  }

  const updateRole = async (id: number, payload: { name: string }) => {
    try {
      const res = await api.put(`/roles/${id}`, payload, {
        headers: { Authorization: `Bearer ${userStore.token}` }
      })
      const updatedRole = res.data.data || res.data
      const index = roles.value.findIndex(r => r.id === id)
      if (index !== -1) roles.value[index] = updatedRole
      cachedRoles = roles.value
      showAlert({ type: 'success', title: 'Успех', message: `Роль "${payload.name}" обновлена` })
    } catch (err: any) {
      showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || 'Не удалось обновить роль' })
    }
  }

  const deleteRole = async (id: number) => {
    try {
      await api.delete(`/roles/${id}`, {
        headers: { Authorization: `Bearer ${userStore.token}` }
      })
      roles.value = roles.value.filter(r => r.id !== id)
      cachedRoles = roles.value
      showAlert({ type: 'success', title: 'Успех', message: 'Роль удалена' })
    } catch (err: any) {
      showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || 'Не удалось удалить роль' })
    }
  }

  return { roles, loading, error, loadRoles, createRole, updateRole, deleteRole }
}
