<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Хэдер -->
    <header class="w-full bg-white shadow-md p-4 flex justify-between items-center">
      <h1 class="text-xl font-semibold text-gray-800">Главная</h1>
      <button
        @click="logout"
        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
      >
        Выйти
      </button>
    </header>

    <!-- Основной контент -->
    <main class="flex-1 flex flex-col items-center gap-6 p-6">
      <h2 class="text-3xl font-bold mb-4">Добро пожаловать, {{ user?.name }}!</h2>
      <p class="text-gray-700 mb-6">Вы находитесь в личном кабинете.</p>

      <!-- Таблица прихода -->
      <IncomeTable />

      <!-- Таблица расхода (если сделаете аналогично) -->
      <!-- <ExpenseTable /> -->
    </main>
  </div>
</template>

<script setup lang="ts">
import { useUserStore } from '@/stores/userStore'
import { useRouter } from 'vue-router'

// Импорт компонента таблицы прихода
import IncomeTable from '@/components/IncomeTable.vue'
// import ExpenseTable from '@/components/ExpenseTable.vue' // когда будете делать расходы

const userStore = useUserStore()
const router = useRouter()
const user = userStore.user

const logout = async () => {
  await userStore.logout()
  router.push('/login')
}
</script>
