<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- фон с блюром -->
    <div class="absolute inset-0 bg-white/30 backdrop-blur-sm transition-opacity" @click="emit('close')"></div>

    <!-- модалка -->
    <div class="relative bg-white p-6 rounded-2xl shadow-xl w-96 max-h-[90vh] overflow-auto border z-10" @click.stop>
      <h2 class="text-2xl font-semibold mb-4 text-gray-800">Сменить пароль</h2>
      <p class="mb-4 text-gray-700">
        Пользователь: <strong>{{ user.first_name || user.name }}</strong>
      </p>

      <!-- Текущий пароль -->
      <div class="relative mb-4">
        <label class="block text-sm font-medium text-gray-600 mb-1">Текущий пароль</label>
        <input
          v-model="currentPassword"
          :type="showCurrentPassword ? 'text' : 'password'"
          class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition pr-10"
        />
        <button
          type="button"
          @click="showCurrentPassword = !showCurrentPassword"
          class="absolute right-2 top-center text-gray-500 hover:text-gray-700"
        >
          <svg v-if="!showCurrentPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
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

      <!-- Новый пароль -->
      <div class="relative mb-4">
        <label class="block text-sm font-medium text-gray-600 mb-1">Новый пароль</label>
        <input
          v-model="newPassword"
          :type="showNewPassword ? 'text' : 'password'"
          class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition pr-10"
        />
        <button
          type="button"
          @click="showNewPassword = !showNewPassword"
          class="absolute right-2 top-center text-gray-500 hover:text-gray-700"
        >
          <svg v-if="!showNewPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
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

      <!-- Подтверждение нового пароля -->
      <div class="relative mb-4">
        <label class="block text-sm font-medium text-gray-600 mb-1">Подтверждение нового пароля</label>
        <input
          v-model="newPasswordConfirmation"
          :type="showConfirmPassword ? 'text' : 'password'"
          class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition pr-10"
        />
        <button
          type="button"
          @click="showConfirmPassword = !showConfirmPassword"
          class="absolute right-2 top-center text-gray-500 hover:text-gray-700"
        >
          <svg v-if="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
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

      <div class="flex justify-end gap-3">
        <button @click="emit('close')" class="px-4 py-2 rounded-lg border bg-gray-100 hover:bg-gray-200 transition">
          Отмена
        </button>
        <button @click="save" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition shadow">
          Сохранить
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { User } from '@/api/users'

interface Props { user: User }
const props = defineProps<Props>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'save', payload: { current_password?: string; new_password: string; new_password_confirmation: string }): void
}>()

const currentPassword = ref('')
const newPassword = ref('')
const newPasswordConfirmation = ref('')

const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)

const save = () => {
  emit('save', {
    current_password: currentPassword.value || undefined,
    new_password: newPassword.value,
    new_password_confirmation: newPasswordConfirmation.value
  })
}
</script>

<style scoped>
.top-center {
  top: calc(70%);
  transform: translateY(-50%);
}
</style>
