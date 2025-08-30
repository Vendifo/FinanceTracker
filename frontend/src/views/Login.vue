<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50">
    <h1 class="text-3xl font-bold mb-4">Login</h1>
    <input v-model="email" type="email" placeholder="Email" class="mb-2 p-2 border rounded"/>
    <input v-model="password" type="password" placeholder="Password" class="mb-4 p-2 border rounded"/>
    <button @click="login" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
      Войти
    </button>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/userStore'

const email = ref('')
const password = ref('')
const router = useRouter()
const userStore = useUserStore()

const login = async () => {
  try {
    await userStore.login(email.value, password.value)
    router.push('/dashboard')
  } catch (e: any) {
    alert(e.response?.data?.message || e.message)
  }
}
</script>
