<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded shadow-md">
      <h1 class="text-2xl font-bold mb-6 text-center">Регистрация</h1>
      <form @submit.prevent="register" class="space-y-4">
        <input v-model="name" type="text" placeholder="Имя" class="w-full p-3 border rounded" />
        <input v-model="email" type="email" placeholder="Email" class="w-full p-3 border rounded" />
        <input v-model="password" type="password" placeholder="Пароль" class="w-full p-3 border rounded" />
        <input v-model="passwordConfirmation" type="password" placeholder="Подтверждение пароля" class="w-full p-3 border rounded" />
        <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded hover:bg-blue-600 transition">Зарегистрироваться</button>
      </form>
      <p class="mt-4 text-center text-gray-600">
        Уже есть аккаунт? <router-link to="/login" class="text-blue-500 hover:underline">Войти</router-link>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/userStore'

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const router = useRouter()
const userStore = useUserStore()

const register = async () => {
  try {
    await userStore.register(name.value, email.value, password.value, passwordConfirmation.value)
    alert('Пользователь создан')
    router.push('/login')
  } catch (e: any) {
    alert(e.response?.data?.message || e.message)
  }
}
</script>
