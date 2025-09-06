<template>
  <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded shadow w-96 max-h-[90vh] overflow-auto">
      <h2 class="text-xl font-bold mb-4">Сменить пароль</h2>
      <p class="mb-2">Пользователь: <strong>{{ user.first_name || user.name }}</strong></p>

      <div class="flex flex-col gap-2 mb-4">
        <label>Текущий пароль</label>
        <input v-model="currentPassword" type="password" class="border px-2 py-1 rounded" />

        <label>Новый пароль</label>
        <input v-model="newPassword" type="password" class="border px-2 py-1 rounded" />

        <label>Подтверждение нового пароля</label>
        <input v-model="newPasswordConfirmation" type="password" class="border px-2 py-1 rounded" />
      </div>

      <div class="flex justify-end gap-2">
        <button @click="emit('close')" class="px-3 py-1 rounded border">Отмена</button>
        <button @click="save" class="px-3 py-1 rounded bg-green-500 text-white hover:bg-green-600">Сохранить</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { User } from '@/api/users'

interface Props { user: User }
const props = defineProps<Props>()

// Emits
const emit = defineEmits<{
  (e: 'close'): void
  (e: 'save', payload: { current_password?: string; new_password: string; new_password_confirmation: string }): void
}>()

// Form state
const currentPassword = ref('')
const newPassword = ref('')
const newPasswordConfirmation = ref('')

// Save handler: emit payload с правильными именами полей
const save = () => {
  emit('save', {
    current_password: currentPassword.value || undefined,
    new_password: newPassword.value,
    new_password_confirmation: newPasswordConfirmation.value
  })

}
</script>
