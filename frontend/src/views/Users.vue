<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 flex flex-col gap-6 p-6 w-full max-w-6xl mx-auto">
      <h1 class="text-2xl font-bold mb-4">Пользователи</h1>

      <!-- Таблица пользователей -->
      <table class="min-w-full bg-white rounded shadow overflow-hidden">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 text-left">ID</th>
            <th class="px-4 py-2 text-left">Имя</th>
            <th class="px-4 py-2 text-left">Фамилия</th>
            <th class="px-4 py-2 text-left">Отчество</th>
            <th class="px-4 py-2 text-left">Email</th>
            <th class="px-4 py-2 text-left">Роль</th>
            <th class="px-4 py-2 text-left">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-2">{{ user.id }}</td>
            <td class="px-4 py-2">{{ user.first_name || user.name }}</td>
            <td class="px-4 py-2">{{ user.last_name || '-' }}</td>
            <td class="px-4 py-2">{{ user.middle_name || '-' }}</td>
            <td class="px-4 py-2">{{ user.email }}</td>
            <td class="px-4 py-2">{{ user.role?.name || '-' }}</td>
            <td class="px-4 py-2 flex gap-2">
              <button
                @click="openEditUserModal(user)"
                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition"
              >
                Редактировать
              </button>
              <button
                @click="openChangePasswordModal(user)"
                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition"
              >
                Сменить пароль
              </button>
              <button
                @click="openDeleteModal(user)"
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition"
              >
                Удалить
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="loading" class="mt-4 text-gray-500">Загрузка...</div>
      <div v-if="error" class="mt-4 text-red-500">{{ error }}</div>

      <!-- Модальное окно редактирования -->
      <div v-if="modalUser" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow w-96 max-h-[90vh] overflow-auto">
          <h2 class="text-xl font-bold mb-4">Редактировать пользователя</h2>

          <div class="flex flex-col gap-2 mb-4">
            <label>Имя</label>
            <input v-model="modalUser.first_name" class="border px-2 py-1 rounded" />

            <label>Фамилия</label>
            <input v-model="modalUser.last_name" class="border px-2 py-1 rounded" />

            <label>Отчество</label>
            <input v-model="modalUser.middle_name" class="border px-2 py-1 rounded" />

            <label>Email</label>
            <input v-model="modalUser.email" class="border px-2 py-1 rounded" />

            <label>Роль</label>
            <select v-model="selectedRoleId" class="border px-2 py-1 rounded">
              <option value="" disabled>Выберите роль</option>
              <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
            </select>
          </div>

          <div class="flex justify-end gap-2">
            <button @click="closeModal" class="px-3 py-1 rounded border">Отмена</button>
            <button @click="saveUser" class="px-3 py-1 rounded bg-green-500 text-white hover:bg-green-600">Сохранить</button>
          </div>
        </div>
      </div>

      <!-- Модальное окно смены пароля -->
      <div v-if="changePasswordUser" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow w-96 max-h-[90vh] overflow-auto">
          <h2 class="text-xl font-bold mb-4">Сменить пароль</h2>

          <p class="mb-2">Пользователь: <strong>{{ changePasswordUser.first_name || changePasswordUser.name }}</strong></p>

          <div class="flex flex-col gap-2 mb-4">
            <label>Текущий пароль</label>
            <input v-model="currentPassword" type="password" class="border px-2 py-1 rounded" />

            <label>Новый пароль</label>
            <input v-model="newPassword" type="password" class="border px-2 py-1 rounded" />

            <label>Подтверждение нового пароля</label>
            <input v-model="newPasswordConfirmation" type="password" class="border px-2 py-1 rounded" />
          </div>

          <div class="flex justify-end gap-2">
            <button @click="closeChangePasswordModal" class="px-3 py-1 rounded border">Отмена</button>
            <button @click="savePassword" class="px-3 py-1 rounded bg-green-500 text-white hover:bg-green-600">Сохранить</button>
          </div>
        </div>
      </div>

      <!-- Модальное окно подтверждения удаления -->
      <div v-if="deleteUserModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow w-80">
          <h2 class="text-lg font-bold mb-4">Удалить пользователя?</h2>
          <p class="mb-4">
            Вы действительно хотите удалить
            <strong>{{ deleteUserModal.first_name || deleteUserModal.name }}</strong>?
          </p>

          <div class="flex justify-end gap-2">
            <button @click="deleteUserModal = null" class="px-3 py-1 rounded border">Отмена</button>
            <button @click="confirmDeleteUser" class="px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600">Удалить</button>
          </div>
        </div>
      </div>

    </main>
  </div>
</template>

<script setup lang="ts">
import Header from '@/components/Header.vue'
import { ref, onMounted } from 'vue'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'

interface Role { id: number; name: string }
interface User { id: number; name: string; first_name?: string; last_name?: string; middle_name?: string; email: string; role?: Role }

const userStore = useUserStore()

const users = ref<User[]>([])
const roles = ref<Role[]>([])
const loading = ref(false)
const error = ref<string | null>(null)

// Модальные состояния
const modalUser = ref<User | null>(null)
const selectedRoleId = ref<number | ''>('')
const deleteUserModal = ref<User | null>(null)
const changePasswordUser = ref<User | null>(null)
const currentPassword = ref('')
const newPassword = ref('')
const newPasswordConfirmation = ref('')

// Заголовки с токеном
const authHeaders = () => ({ Authorization: `Bearer ${userStore.token}` })

// Загрузка пользователей и ролей
const fetchUsersAndRoles = async () => {
  loading.value = true
  error.value = null
  try {
    const [usersRes, rolesRes] = await Promise.all([
      api.get<{ data: User[] }>('/users', { headers: authHeaders() }),
      api.get<{ data: Role[] }>('/roles', { headers: authHeaders() })
    ])
    users.value = usersRes.data.data
    roles.value = rolesRes.data.data
  } catch (err: any) {
    error.value = err.response?.data?.message || err.message || 'Ошибка при загрузке'
  } finally { loading.value = false }
}

// Редактирование пользователя
const openEditUserModal = (user: User) => {
  modalUser.value = { ...user }
  selectedRoleId.value = user.role?.id || ''
}
const closeModal = () => { modalUser.value = null; selectedRoleId.value = '' }

const saveUser = async () => {
  if (!modalUser.value) return
  try {
    await api.put(`/users/${modalUser.value.id}`, {
      first_name: modalUser.value.first_name,
      last_name: modalUser.value.last_name,
      middle_name: modalUser.value.middle_name,
      email: modalUser.value.email
    }, { headers: authHeaders() })

    if (selectedRoleId.value) {
      await api.post(`/users/${modalUser.value.id}/assign-role`, { role_id: selectedRoleId.value }, { headers: authHeaders() })
      modalUser.value.role = roles.value.find(r => r.id === selectedRoleId.value)
    }

    const index = users.value.findIndex(u => u.id === modalUser.value!.id)
    if (index !== -1) users.value[index] = { ...modalUser.value }
    closeModal()
    alert('Пользователь обновлён')
  } catch (err: any) {
    alert(err.response?.data?.message || err.message || 'Ошибка при сохранении')
  }
}

// Удаление пользователя
const openDeleteModal = (user: User) => deleteUserModal.value = user
const confirmDeleteUser = async () => {
  if (!deleteUserModal.value) return
  try {
    await api.delete(`/users/${deleteUserModal.value.id}`, { headers: authHeaders() })
    users.value = users.value.filter(u => u.id !== deleteUserModal.value!.id)
    deleteUserModal.value = null
  } catch (err: any) {
    alert(err.response?.data?.message || err.message || 'Ошибка при удалении')
  }
}

// Смена пароля
const openChangePasswordModal = (user: User) => {
  changePasswordUser.value = user
  currentPassword.value = ''
  newPassword.value = ''
  newPasswordConfirmation.value = ''
}
const closeChangePasswordModal = () => { changePasswordUser.value = null }

const savePassword = async () => {
  if (!changePasswordUser.value) return
  if (!newPassword.value || !newPasswordConfirmation.value) return alert('Заполните все поля')
  if (newPassword.value !== newPasswordConfirmation.value) return alert('Пароли не совпадают')

  try {
    const payload: any = {
      new_password: newPassword.value,
      new_password_confirmation: newPasswordConfirmation.value
    }
    if (changePasswordUser.value.id === userStore.user.id) {
      if (!currentPassword.value) return alert('Введите текущий пароль')
      payload.current_password = currentPassword.value
    }

    await api.post(`/users/${changePasswordUser.value.id}/change-password`, payload, { headers: authHeaders() })
    alert('Пароль успешно изменён')
    closeChangePasswordModal()
  } catch (err: any) {
    alert(err.response?.data?.message || err.message || 'Ошибка при смене пароля')
  }
}

onMounted(fetchUsersAndRoles)
</script>
