<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50">
    <h1 class="text-3xl font-bold mb-4">Добро пожаловать, {{ user?.name }}!</h1>
    <button @click="logout" class="mt-6 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
      Выйти
    </button>
  </div>
</template>

<script setup lang="ts">
import { useUserStore } from '@/stores/userStore'
import { useRouter } from 'vue-router'

const userStore = useUserStore()
const router = useRouter()
const user = userStore.user

const logout = async () => {
  await userStore.logout()
  router.push('/login')
}
</script>
