<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- фон с блюром -->
    <div class="absolute inset-0 bg-white/30 backdrop-blur-sm transition-opacity" @click="$emit('close')"></div>

    <!-- модалка -->
    <div class="relative bg-white p-6 rounded-2xl shadow-xl w-96 max-h-[90vh] overflow-auto border z-10" @click.stop>
      <h2 class="text-2xl font-semibold mb-4 text-gray-800">Новый пользователь</h2>

      <div class="flex flex-col gap-3 mb-6">
        <!-- Никнейм -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Никнейм</label>
          <input v-model="name" type="text"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
        </div>

        <!-- Имя, Фамилия, Отчество, Email -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Имя</label>
          <input v-model="firstName" type="text"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Фамилия</label>
          <input v-model="lastName" type="text"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Отчество</label>
          <input v-model="middleName" type="text"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
          <input v-model="email" type="email"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />
        </div>

        <div class="relative">
          <label class="block text-sm font-medium text-gray-600 mb-1">Пароль</label>
          <input v-model="password" :type="showPassword ? 'text' : 'password'"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition pr-10" />
          <button type="button" @click="showPassword = !showPassword"
            class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-gray-700">
            <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.969 9.969 0 012.178-3.568M6.2 6.2A9.969 9.969 0 0012 5c4.477 0 8.268 2.943 9.542 7a9.953 9.953 0 01-4.043 5.197M15 12a3 3 0 01-4.243 2.829M9.88 9.88A3 3 0 0115 12m-6.708 6.708L20.485 6.515M3.515 3.515l4.243 4.243" />
            </svg>
          </button>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium text-gray-600 mb-1">Подтверждение пароля</label>
          <input v-model="passwordConfirmation" :type="showPasswordConfirm ? 'text' : 'password'"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition pr-10" />
          <button type="button" @click="showPasswordConfirm = !showPasswordConfirm"
            class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-gray-700">
            <svg v-if="!showPasswordConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
              viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.969 9.969 0 012.178-3.568M6.2 6.2A9.969 9.969 0 0012 5c4.477 0 8.268 2.943 9.542 7a9.953 9.953 0 01-4.043 5.197M15 12a3 3 0 01-4.243 2.829M9.88 9.88A3 3 0 0115 12m-6.708 6.708L20.485 6.515M3.515 3.515l4.243 4.243" />
            </svg>
          </button>
        </div>


        <!-- Выбор роли -->
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Роль</label>
          <select v-model="roleId"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
            <option value="">— Выберите роль —</option>
            <option v-for="role in roles" :key="role.id" :value="role.id">
              {{ role.name }}
            </option>
          </select>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <button @click="$emit('close')" class="px-4 py-2 rounded-lg border bg-gray-100 hover:bg-gray-200 transition">
          Отмена
        </button>
        <button @click="saveUser"
          class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition shadow">
          Создать
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

interface Role {
  id: number
  name: string
}

const props = defineProps<{ roles: Role[] }>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'save', payload: {
    name: string
    email: string
    password: string
    password_confirmation: string
    first_name?: string
    last_name?: string
    middle_name?: string
    role_id?: number
  }): void
}>()

const name = ref('')
const firstName = ref('')
const lastName = ref('')
const middleName = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const roleId = ref<number | ''>('')

// show/hide password
const showPassword = ref(false)
const showPasswordConfirm = ref(false)

const saveUser = () => {
  emit('save', {
    name: name.value,
    email: email.value,
    password: password.value,
    password_confirmation: passwordConfirmation.value,
    first_name: firstName.value || undefined,
    last_name: lastName.value || undefined,
    middle_name: middleName.value || undefined,
    role_id: roleId.value || undefined,
  })
}
</script>
