<template>
  <header class="w-full bg-white shadow-md p-4 flex justify-between items-center">
    <!-- Логотип/Название -->
    <h1 class="text-xl font-semibold text-gray-800">Главная</h1>

    <!-- Меню -->
    <nav class="flex gap-6">
      <RouterLink
        v-for="link in links"
        :key="link.path"
        :to="link.path"
        class="text-gray-700 hover:text-blue-600 transition"
        active-class="font-semibold text-blue-600"
      >
        {{ link.name }}
      </RouterLink>
    </nav>

    <!-- Пользователь -->
    <div class="flex items-center gap-4">
      <span>{{ user?.name }}</span>
      <button
        @click="logout"
        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
      >
        Выйти
      </button>
    </div>
  </header>
</template>

<script setup lang="ts">
import { useUserStore } from '@/stores/userStore'
import { useRouter, RouterLink } from 'vue-router'

const userStore = useUserStore()
const router = useRouter()
const user = userStore.user

const logout = async () => {
  await userStore.logout()
  router.push('/login')
}

const links = [
  { name: 'Касса', path: '/dashboard' },
  { name: 'Пользователи', path: '/users' },
  { name: 'Отчеты', path: '/reports' },
]
</script>
