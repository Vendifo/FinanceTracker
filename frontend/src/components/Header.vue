<template>
  <header class="w-full bg-white shadow-md p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-gray-800">Касса крым</h1>

    <nav class="flex gap-6">
      <!-- Касса: все авторизованные пользователи -->
      <RouterLink to="/dashboard" class="relative text-gray-700 hover:text-blue-600 transition"
        active-class="font-semibold text-blue-600">
        Касса
        <span class="absolute left-0 -bottom-1 w-full h-0.5 bg-blue-600" v-if="$route.path === '/dashboard'"></span>
      </RouterLink>

      <!-- Пользователи: только admin и manager -->
      <ProtectedRole :roles="['admin', 'manager']">
        <RouterLink to="/users" class="relative text-gray-700 hover:text-blue-600 transition"
          active-class="font-semibold text-blue-600">
          Пользователи
          <span class="absolute left-0 -bottom-1 w-full h-0.5 bg-blue-600" v-if="$route.path === '/users'"></span>
        </RouterLink>
      </ProtectedRole>

      <!-- Отчеты: admin, manager и accountant (можно указать конкретно, например accountant) -->
      <ProtectedRole :roles="['admin', 'manager', 'accountant']">
        <RouterLink to="/reports" class="relative text-gray-700 hover:text-blue-600 transition"
          active-class="font-semibold text-blue-600">
          Отчеты
          <span class="absolute left-0 -bottom-1 w-full h-0.5 bg-blue-600" v-if="$route.path === '/reports'"></span>
        </RouterLink>
      </ProtectedRole>
    </nav>


    <div class="flex items-center gap-4">
      <div class="flex items-center gap-2">
        <div v-if="!user?.avatar"
          class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white font-semibold uppercase">
          {{ initials }}
        </div>

        <img v-else :src="user.avatar" alt="Avatar" class="w-8 h-8 rounded-full object-cover" />
        <span class="text-gray-800 font-medium">{{ user?.name }}</span>
      </div>
      <button @click="logout" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
        Выйти
      </button>
    </div>
  </header>

</template>

<script setup lang="ts">
import { useUserStore } from '@/stores/userStore'
import { useRouter, RouterLink } from 'vue-router'
import { computed } from 'vue'
import ProtectedRole from '@/components/ProtectedRole.vue'
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

const initials = computed(() => {
  if (!user?.name) return ''
  return user.name
    .split(' ')
    .map((n: string) => n[0])
    .join('')
    .toUpperCase()
})
</script>
