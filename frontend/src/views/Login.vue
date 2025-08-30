<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded shadow-md">
      <h1 class="text-2xl font-bold mb-6 text-center">Вход</h1>
      <form @submit.prevent="login" class="space-y-4">
        <input v-model="email" type="email" placeholder="Email"
               class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <input v-model="password" type="password" placeholder="Пароль"
               class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <button type="submit"
                class="w-full bg-blue-500 text-white p-3 rounded hover:bg-blue-600 transition">Войти</button>
      </form>
      <p class="mt-4 text-center text-gray-600">
        Нет аккаунта? <router-link to="/register" class="text-blue-500 hover:underline">Регистрация</router-link>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '../stores/userStore'
import api from '../services/api'

const email = ref('')
const password = ref('')
const router = useRouter()
const userStore = useUserStore()

const login = async () => {
  try {
    const response = await api.post('/login', { email: email.value, password: password.value })
    const { user, token } = response.data
    userStore.setUser(user, token)
    router.push('/dashboard')
  } catch (error: any) {
    alert(error.response?.data?.message || 'Ошибка входа')
  }
}
</script>
