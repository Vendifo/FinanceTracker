<template>
  <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded shadow w-96 max-h-[90vh] overflow-auto">
      <h2 class="text-xl font-bold mb-4">Новый пользователь</h2>

      <div class="flex flex-col gap-2 mb-4">
        <label>Имя</label>
        <input v-model="name" type="text" class="border px-2 py-1 rounded" />

        <label>Email</label>
        <input v-model="email" type="email" class="border px-2 py-1 rounded" />

        <label>Пароль</label>
        <input v-model="password" type="password" class="border px-2 py-1 rounded" />

        <label>Подтверждение пароля</label>
        <input v-model="passwordConfirmation" type="password" class="border px-2 py-1 rounded" />
      </div>

      <div class="flex justify-end gap-2">
        <button @click="$emit('close')" class="px-3 py-1 rounded border">Отмена</button>
        <button @click="saveUser" class="px-3 py-1 rounded bg-green-500 text-white hover:bg-green-600">Создать</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'save', payload: { name: string; email: string; password: string; password_confirmation: string }): void
}>()

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')

const saveUser = () => {
  emit('save', {
    name: name.value,
    email: email.value,
    password: password.value,
    password_confirmation: passwordConfirmation.value
  })
}
</script>
