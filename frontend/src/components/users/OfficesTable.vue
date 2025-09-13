<template>
  <div class="bg-white text-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-xl font-semibold">Офисы</h3>
      <button @click="openAddOffice" class="p-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
        title="Добавить офис">
        <Plus class="w-5 h-5" />
      </button>
    </div>

    <!-- Состояния -->
    <div v-if="loading" class="text-gray-500 mb-2 text-sm">Загрузка...</div>
    <div v-if="error" class="text-red-500 mb-2 text-sm">{{ error }}</div>

    <!-- Таблица офисов -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-gray-600 font-medium">
          <tr>
            <th class="px-3 py-2 text-left">Название</th>
            <th class="px-3 py-2 text-right">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="office in offices" :key="office.id"
            class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition border-b border-gray-200">
            <td class="px-3 py-2 text-gray-700">{{ office.name }}</td>
            <td class="px-3 py-2 flex justify-end gap-2">
              <button @click="openEditOffice(office)"
                class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition" title="Редактировать">
                <Edit class="w-4 h-4" />
              </button>
              <button @click="openDeleteOffice(office)"
                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded transition" title="Удалить">
                <Trash2 class="w-4 h-4" />
              </button>
            </td>
          </tr>

          <tr v-if="offices.length === 0">
            <td colspan="2" class="text-center py-4 text-gray-500 text-sm border-b border-gray-200">
              Офисов нет
            </td>
          </tr>

          <tr v-if="loading">
            <td colspan="2" class="text-center py-4 text-gray-500 text-sm border-b border-gray-200">
              Загрузка...
            </td>
          </tr>

          <tr v-if="error">
            <td colspan="2" class="text-center py-4 text-red-500 text-sm border-b border-gray-200">
              {{ error }}
            </td>
          </tr>
        </tbody>

      </table>
    </div>

    <!-- Модалки -->
    <!-- Добавление -->
    <div v-if="addOfficeModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-white/30 backdrop-blur-sm" @click="closeAddOffice"></div>
      <div class="relative bg-white p-6 rounded-2xl shadow-xl w-96 z-10" @click.stop>
        <h3 class="text-lg font-bold mb-4">Новый офис</h3>
        <input v-model="officeForm.name" type="text" placeholder="Название офиса"
          class="border px-3 py-2 rounded w-full mb-4 focus:outline-none focus:ring-2 focus:ring-green-500" />
        <div class="flex justify-end gap-2">
          <button @click="closeAddOffice" class="px-3 py-1 border rounded">Отмена</button>
          <button @click="createOfficeHandler" :disabled="actionLoading"
            class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition disabled:opacity-50">
            {{ actionLoading ? 'Сохраняем...' : 'Создать' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Редактирование -->
    <div v-if="editOfficeModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-white/30 backdrop-blur-sm" @click="closeEdit"></div>
      <div class="relative bg-white p-6 rounded-2xl shadow-xl w-96 z-10" @click.stop>
        <h3 class="text-lg font-bold mb-4">Редактировать офис</h3>
        <input v-model="officeForm.name" type="text" placeholder="Название офиса"
          class="border px-3 py-2 rounded w-full mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <div class="flex justify-end gap-2">
          <button @click="closeEdit" class="px-3 py-1 border rounded">Отмена</button>
          <button @click="updateOfficeHandler" :disabled="actionLoading"
            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition disabled:opacity-50">
            {{ actionLoading ? 'Сохраняем...' : 'Сохранить' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Удаление -->
    <div v-if="deleteOfficeModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-white/30 backdrop-blur-sm" @click="closeDelete"></div>
      <div class="relative bg-white p-6 rounded-2xl shadow-xl w-96 z-10" @click.stop>
        <h3 class="text-lg font-bold mb-4">Удалить офис?</h3>
        <p class="mb-4">Вы действительно хотите удалить офис <strong>{{ deleteOfficeModal.name }}</strong>?</p>
        <div class="flex justify-end gap-2">
          <button @click="closeDelete" class="px-3 py-1 border rounded">Отмена</button>
          <button @click="deleteOfficeHandler" :disabled="actionLoading"
            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition disabled:opacity-50">
            {{ actionLoading ? 'Удаляем...' : 'Удалить' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'
import { useAlert } from '@/composables/useAlert'
import { Plus, Edit, Trash2 } from 'lucide-vue-next'

const { showAlert } = useAlert()
const userStore = useUserStore()

const offices = ref<Array<{ id: number; name: string }>>([])
const loading = ref(false)
const error = ref<string | null>(null)
const actionLoading = ref(false)

const addOfficeModal = ref(false)
const editOfficeModal = ref<{ id: number; name: string } | null>(null)
const deleteOfficeModal = ref<{ id: number; name: string } | null>(null)

const officeForm = ref<{ name: string }>({ name: '' })

const authHeaders = () => ({ Authorization: `Bearer ${userStore.token}` })

// Загрузка офисов
const fetchOffices = async () => {
  loading.value = true
  try {
    const res = await api.get('/offices', { headers: authHeaders() })
    offices.value = Array.isArray(res.data) ? res.data : res.data.data || []
  } catch (err: any) {
    error.value = err.message || 'Ошибка загрузки'
    offices.value = []
  } finally {
    loading.value = false
  }
}
onMounted(fetchOffices)

// Добавление
const openAddOffice = () => { officeForm.value.name = ''; addOfficeModal.value = true }
const closeAddOffice = () => (addOfficeModal.value = false)
const createOfficeHandler = async () => {
  if (!officeForm.value.name.trim()) return showAlert({ type: 'error', title: 'Ошибка', message: 'Введите название офиса' })
  try {
    actionLoading.value = true
    const res = await api.post('/offices', { name: officeForm.value.name }, { headers: authHeaders() })
    offices.value.push(res.data.data || res.data)
    showAlert({ type: 'success', title: 'Успех', message: 'Офис создан' })
    closeAddOffice()
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  } finally { actionLoading.value = false }
}

// Редактирование
const openEditOffice = (office: { id: number; name: string }) => { editOfficeModal.value = { ...office }; officeForm.value.name = office.name }
const closeEdit = () => { editOfficeModal.value = null; officeForm.value.name = '' }
const updateOfficeHandler = async () => {
  if (!editOfficeModal.value || !officeForm.value.name.trim()) return
  try {
    actionLoading.value = true
    const res = await api.put(`/offices/${editOfficeModal.value.id}`, { name: officeForm.value.name }, { headers: authHeaders() })
    const idx = offices.value.findIndex((o) => o.id === editOfficeModal.value?.id)
    if (idx !== -1) offices.value[idx] = res.data.data || res.data
    showAlert({ type: 'success', title: 'Успех', message: 'Офис обновлён' })
    closeEdit()
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  } finally { actionLoading.value = false }
}

// Удаление
const openDeleteOffice = (office: { id: number; name: string }) => { deleteOfficeModal.value = { ...office } }
const closeDelete = () => (deleteOfficeModal.value = null)
const deleteOfficeHandler = async () => {
  if (!deleteOfficeModal.value) return
  try {
    actionLoading.value = true
    await api.delete(`/offices/${deleteOfficeModal.value.id}`, { headers: authHeaders() })
    offices.value = offices.value.filter(o => o.id !== deleteOfficeModal.value?.id)
    showAlert({ type: 'success', title: 'Успех', message: 'Офис удалён' })
    closeDelete()
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  } finally { actionLoading.value = false }
}
</script>
