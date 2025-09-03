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
    <main class="flex-1 flex flex-col items-center gap-6 p-6 w-full max-w-5xl">


       <div class="mb-4 w-full max-w-sm">
        <label class="block mb-1 font-semibold">Офис</label>
        <select v-model="selectedOfficeId" class="border px-2 py-1 rounded w-full">
          <option :value="null">Все офисы</option>
          <option v-for="office in offices" :key="office.id" :value="office.id">
            {{ office.name }}
          </option>
        </select>
      </div>

      <h2 class="text-3xl font-bold mb-4">Добро пожаловать, {{ user?.name }}!</h2>
      <p class="text-gray-700 mb-6">Вы находитесь в личном кабинете.</p>

      <!-- Таблица прихода -->
      <IncomeTable :office-id="selectedOfficeId" />

      <!-- Таблица расхода (если нужно) -->
      <!-- <ExpenseTable :office-id="selectedOfficeId" /> -->
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useUserStore } from '@/stores/userStore'
import { useRouter } from 'vue-router'
import api from '@/axios'
import IncomeTable from '@/components/IncomeTable.vue'

const userStore = useUserStore()
const router = useRouter()
const user = userStore.user

const offices = ref<any[]>([])
const selectedOfficeId = ref<number | null>(null)

// Получение списка офисов
const fetchOffices = async () => {
  try {
    const res = await api.get('/offices', { headers: { Authorization: `Bearer ${userStore.token}` } })
    offices.value = res.data
    if (offices.value.length > 0) {
      // Автоматически подставляем первый офис
      selectedOfficeId.value = offices.value[0].id
    }
  } catch (err) {
    console.error('Ошибка при загрузке офисов:', err)
  }
}

// Выход
const logout = async () => {
  await userStore.logout()
  router.push('/login')
}

onMounted(() => {
  fetchOffices()
})
</script>
