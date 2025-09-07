<template>
  <div>
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">Роли</h2>
      <button @click="openAddRole" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
        Добавить роль
      </button>
    </div>

    <!-- Таблица ролей -->
    <table class="w-full bg-white rounded shadow overflow-hidden">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Название</th>
          <th class="px-4 py-2">Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="role in roles" :key="role.id" class="border-b">
          <td class="px-4 py-2">{{ role.id }}</td>
          <td class="px-4 py-2">{{ role.name }}</td>
          <td class="px-4 py-2 flex gap-2">
            <button @click="editRole(role)" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Редактировать</button>
            <button @click="deleteRoleHandler(role.id)" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">Удалить</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Модалка добавления -->
    <div v-if="addRoleModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white p-6 rounded shadow w-96">
        <h3 class="text-lg font-bold mb-4">Новая роль</h3>
        <input v-model="roleForm.name" type="text" placeholder="Название роли" class="border px-2 py-1 rounded w-full mb-4" />
        <div class="flex justify-end gap-2">
          <button @click="closeAddRole" class="px-3 py-1 border rounded">Отмена</button>
          <button @click="createRoleHandler" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Создать</button>
        </div>
      </div>
    </div>

    <!-- Модалка редактирования -->
    <div v-if="editRoleModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white p-6 rounded shadow w-96">
        <h3 class="text-lg font-bold mb-4">Редактировать роль</h3>
        <input v-model="roleForm.name" type="text" placeholder="Название роли" class="border px-2 py-1 rounded w-full mb-4" />
        <div class="flex justify-end gap-2">
          <button @click="closeEdit" class="px-3 py-1 border rounded">Отмена</button>
          <button @click="updateRoleHandler" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Сохранить</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoles } from '@/composables/useRoles'
import { useAlert } from '@/composables/useAlert'

const { showAlert } = useAlert()
const { roles, createRole, updateRole, deleteRole, loadRoles } = useRoles()

const addRoleModal = ref(false)
const editRoleModal = ref<{id:number,name:string} | null>(null)
const roleForm = ref<{name:string}>({ name: '' })

// Добавление роли
const openAddRole = () => {
  roleForm.value.name = ''
  addRoleModal.value = true
}
const closeAddRole = () => addRoleModal.value = false

const createRoleHandler = async () => {
  if (!roleForm.value.name) {
    showAlert({ type: 'error', title: 'Ошибка', message: 'Введите название роли' })
    return
  }
  try {
    await createRole({ name: roleForm.value.name })
    showAlert({ type: 'success', title: 'Успех', message: 'Роль создана' })
    roleForm.value.name = ''
    addRoleModal.value = false
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  }
}

// Редактирование роли
const editRole = (role: {id:number,name:string}) => {
  editRoleModal.value = { ...role }
  roleForm.value.name = role.name
}

const closeEdit = () => {
  editRoleModal.value = null
  roleForm.value.name = ''
}

const updateRoleHandler = async () => {
  if (!editRoleModal.value) return
  if (!roleForm.value.name) {
    showAlert({ type: 'error', title: 'Ошибка', message: 'Введите название роли' })
    return
  }
  try {
    await updateRole(editRoleModal.value.id, { name: roleForm.value.name })
    showAlert({ type: 'success', title: 'Успех', message: 'Роль обновлена' })
    closeEdit()
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  }
}

// Удаление роли
const deleteRoleHandler = async (id: number) => {
  // Используем встроенный confirm, но можно потом заменить на модалку
  const confirmDelete = window.confirm('Удалить роль?')
  if (!confirmDelete) return
  try {
    await deleteRole(id)
    showAlert({ type: 'success', title: 'Успех', message: 'Роль удалена' })
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  }
}

onMounted(() => {
  loadRoles()
})
</script>
