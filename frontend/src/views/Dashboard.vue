<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Хэдер -->
    <Header />

    <!-- Основной контент -->
    <main class="flex-1 flex flex-col items-center gap-6 p-6">


      <div class="min-h-screen bg-gray-50 p-6">
        <FinanceDashboard />
      </div>

    </main>
  </div>
</template>

<script setup lang="ts">
import { useUserStore } from '@/stores/userStore'
import { useRouter } from 'vue-router'
import FinanceDashboard from '@/components/FinanceDashboard.vue'
import Header from '@/components/Header.vue'
// Импорт компонента таблицы прихода

const userStore = useUserStore()
const router = useRouter()
const user = userStore.user

const logout = async () => {
  await userStore.logout()
  router.push('/login')
}
</script>
