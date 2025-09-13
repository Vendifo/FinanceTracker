<template>
  <div class="bg-white text-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-xl font-semibold">Роли</h3>
      <button @click="openAddRole" class="p-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
        title="Добавить роль">
        <Plus class="w-5 h-5" />
      </button>
    </div>

    <!-- Состояния -->
    <div v-if="loading" class="text-gray-500 mb-2 text-sm">Загрузка...</div>
    <div v-if="error" class="text-red-500 mb-2 text-sm">{{ error }}</div>

    <!-- Таблица ролей -->
    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-200 text-sm">
        <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-300">
          <tr>
            <th class="px-3 py-2 text-left">Название</th>
            <th class="px-3 py-2 text-right">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="role in roles" :key="role.id"
            class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition border-b border-gray-200">
            <td class="px-3 py-2 text-gray-700">{{ role.name }}</td>
            <td class="px-3 py-2 flex justify-end gap-2">
              <button @click="openEditRole(role)"
                class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition" title="Редактировать">
                <Edit class="w-4 h-4" />
              </button>
              <button @click="openDeleteRole(role)"
                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded transition" title="Удалить">
                <Trash2 class="w-4 h-4" />
              </button>
            </td>
          </tr>

          <tr v-if="loading">
            <td colspan="2" class="px-3 py-2 text-gray-500 text-center border-b border-gray-200">
              Загрузка...
            </td>
          </tr>

          <tr v-if="error">
            <td colspan="2" class="px-3 py-2 text-red-500 text-center border-b border-gray-200">
              {{ error }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>


    <!-- Модалки -->
    <!-- Добавление -->
    <div v-if="addRoleModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-white/30 backdrop-blur-sm" @click="closeAddRole"></div>
      <div class="relative bg-white p-6 rounded-2xl shadow-xl w-96 z-10" @click.stop>
        <h3 class="text-lg font-bold mb-4">Новая роль</h3>
        <input v-model="roleForm.name" type="text" placeholder="Название роли"
          class="border px-3 py-2 rounded w-full mb-4 focus:outline-none focus:ring-2 focus:ring-green-500" />
        <div class="flex justify-end gap-2">
          <button @click="closeAddRole" class="px-3 py-1 border rounded">Отмена</button>
          <button @click="createRoleHandler" :disabled="actionLoading"
            class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition disabled:opacity-50">
            {{ actionLoading ? 'Сохраняем...' : 'Создать' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Редактирование -->
    <div v-if="editRoleModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-white/30 backdrop-blur-sm" @click="closeEdit"></div>
      <div class="relative bg-white p-6 rounded-2xl shadow-xl w-96 z-10" @click.stop>
        <h3 class="text-lg font-bold mb-4">Редактировать роль</h3>
        <input v-model="roleForm.name" type="text" placeholder="Название роли"
          class="border px-3 py-2 rounded w-full mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <div class="flex justify-end gap-2">
          <button @click="closeEdit" class="px-3 py-1 border rounded">Отмена</button>
          <button @click="updateRoleHandler" :disabled="actionLoading"
            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition disabled:opacity-50">
            {{ actionLoading ? 'Сохраняем...' : 'Сохранить' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Удаление -->
    <div v-if="deleteRoleModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-white/30 backdrop-blur-sm" @click="closeDelete"></div>
      <div class="relative bg-white p-6 rounded-2xl shadow-xl w-96 z-10" @click.stop>
        <h3 class="text-lg font-bold mb-4">Удалить роль?</h3>
        <p class="mb-4">Вы действительно хотите удалить роль <strong>{{ deleteRoleModal.name }}</strong>?</p>
        <div class="flex justify-end gap-2">
          <button @click="closeDelete" class="px-3 py-1 border rounded">Отмена</button>
          <button @click="deleteRoleHandler" :disabled="actionLoading"
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
import { useRoles } from '@/composables/useRoles'
import { useAlert } from '@/composables/useAlert'
import { Edit, Trash2, Plus } from 'lucide-vue-next'

const { showAlert } = useAlert()
const { roles, createRole, updateRole, deleteRole, loadRoles } = useRoles()

// состояние и логика — оставляем без изменений
const loading = ref(false)
const error = ref<string | null>(null)
const actionLoading = ref(false)

const addRoleModal = ref(false)
const editRoleModal = ref<{ id: number; name: string } | null>(null)
const deleteRoleModal = ref<{ id: number; name: string } | null>(null)

const roleForm = ref<{ name: string }>({ name: '' })

const openAddRole = () => { roleForm.value.name = ''; addRoleModal.value = true }
const closeAddRole = () => (addRoleModal.value = false)

const openEditRole = (role: { id: number; name: string }) => { editRoleModal.value = { ...role }; roleForm.value.name = role.name }
const closeEdit = () => { editRoleModal.value = null; roleForm.value.name = '' }

const openDeleteRole = (role: { id: number; name: string }) => { deleteRoleModal.value = { ...role } }
const closeDelete = () => (deleteRoleModal.value = null)

// Добавление
const createRoleHandler = async () => {
  if (!roleForm.value.name) return showAlert({ type: 'error', title: 'Ошибка', message: 'Введите название роли' })
  try {
    actionLoading.value = true
    await createRole({ name: roleForm.value.name })
    showAlert({ type: 'success', title: 'Успех', message: 'Роль создана' })
    roleForm.value.name = ''
    addRoleModal.value = false
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  } finally { actionLoading.value = false }
}

// Редактирование
const updateRoleHandler = async () => {
  if (!editRoleModal.value || !roleForm.value.name) return showAlert({ type: 'error', title: 'Ошибка', message: 'Введите название роли' })
  try {
    actionLoading.value = true
    await updateRole(editRoleModal.value.id, { name: roleForm.value.name })
    showAlert({ type: 'success', title: 'Успех', message: 'Роль обновлена' })
    closeEdit()
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  } finally { actionLoading.value = false }
}

// Удаление
const deleteRoleHandler = async () => {
  if (!deleteRoleModal.value) return
  try {
    actionLoading.value = true
    await deleteRole(deleteRoleModal.value.id)
    showAlert({ type: 'success', title: 'Успех', message: 'Роль удалена' })
    closeDelete()
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  } finally { actionLoading.value = false }
}

onMounted(async () => {
  loading.value = true
  try { await loadRoles() }
  catch (err: any) { error.value = err.message || 'Ошибка загрузки' }
  finally { loading.value = false }
})
</script>
