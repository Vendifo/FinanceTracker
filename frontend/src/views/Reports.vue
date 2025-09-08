<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 flex flex-col items-center gap-6 p-6">
      <div class="w-full max-w-7xl bg-white shadow rounded-2xl p-6">

        <!-- Фильтры -->
        <div class="flex flex-wrap gap-4 mb-6 items-end">
          <input type="date" v-model="dateFrom" class="border p-2 rounded" />
          <input type="date" v-model="dateTo" class="border p-2 rounded" />

          <select v-model="selectedOffice" class="border p-2 rounded">
            <option value="">Все офисы</option>
            <option v-for="office in offices" :key="office.id" :value="office.id">
              {{ office.name }}
            </option>
          </select>

          <select v-model="selectedArticle" class="border p-2 rounded">
            <option value="">Все статьи</option>
            <option v-for="article in articles" :key="article.id" :value="article.id">
              {{ article.name }}
            </option>
          </select>

          <button @click="loadAllData" class="bg-blue-500 text-white px-4 py-2 rounded">
            Загрузить
          </button>
        </div>

        <div class="w-full flex gap-6">
  <!-- Левая колонка: график -->
  <div class="flex-1 bg-white p-4 rounded-lg shadow">
    <h2 class="text-lg font-semibold mb-2">Динамика доходов и расходов</h2>
    <div v-if="loading" class="text-gray-500">Загрузка...</div>
    <div v-else-if="error" class="text-red-500">{{ error }}</div>
    <div v-else-if="chartData" class="h-96">
      <Line :data="chartData" :options="chartOptions" />
    </div>
    <div v-else class="text-gray-500">Нет данных</div>
  </div>

  <!-- Правая колонка -->
<div class="w-64 bg-gray-50 p-4 rounded-lg shadow flex flex-col gap-4">
  <h2 class="text-lg font-semibold mb-2">Баланс за период</h2>

  <!-- Общий баланс -->
  <div v-if="loadingBalance" class="text-gray-500">Загрузка...</div>
  <div v-else-if="errorBalance" class="text-red-500">{{ errorBalance }}</div>
  <div v-else-if="balanceData" class="flex flex-col gap-2 text-center">
    <div>
      <p class="text-sm text-gray-500">Доходы</p>
      <p class="text-xl font-bold text-green-600">{{ balanceData.income }} ₽</p>
    </div>
    <div>
      <p class="text-sm text-gray-500">Расходы</p>
      <p class="text-xl font-bold text-red-600">{{ balanceData.expense }} ₽</p>
    </div>
    <div :class="[balanceData.balance >= 0 ? 'text-green-700' : 'text-red-700']">
      <p class="text-sm text-gray-500">Баланс</p>
      <p class="text-2xl font-bold">{{ balanceData.balance }} ₽</p>
    </div>
  </div>

  <!-- Новый блок: по офисам -->
  <div class="border-t pt-4">
    <h3 class="text-md font-semibold mb-2">По офисам</h3>
    <div v-if="loadingByOffice" class="text-gray-500">Загрузка...</div>
    <div v-else-if="errorByOffice" class="text-red-500">{{ errorByOffice }}</div>
    <table v-else class="w-full text-sm">
      <thead>
        <tr class="text-gray-500 text-left">
          <th class="py-1">Офис</th>
          <th class="py-1 text-right">₽</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="o in byOfficeData" :key="o.office_id" class="border-t">
          <td class="py-1">{{ o.office_name }}</td>
          <td class="py-1 text-right font-medium"
              :class="[o.balance >= 0 ? 'text-green-600' : 'text-red-600']">
            {{ o.balance }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

</div>



      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Header from '@/components/Header.vue'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'

// vue-chartjs
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement,
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement)

const userStore = useUserStore()

// Даты по умолчанию
const today = new Date().toISOString().slice(0, 10)
const weekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().slice(0, 10)
const dateFrom = ref(weekAgo)
const dateTo = ref(today)

// Списки офисов и статей
const offices = ref<any[]>([])
const articles = ref<any[]>([])

const selectedOffice = ref('')
const selectedArticle = ref('')

// Динамика доходов/расходов
const chartData = ref<any>(null)
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { position: 'top' as const },
    title: { display: true, text: 'Доходы и расходы по дням' }
  }
}
const loading = ref(false)
const error = ref<string | null>(null)

// Баланс за период
const balanceData = ref<any>(null)
const loadingBalance = ref(false)
const errorBalance = ref<string | null>(null)

const authHeaders = () => ({ Authorization: `Bearer ${userStore.token}` })

const byOfficeData = ref<any[]>([])
const loadingByOffice = ref(false)
const errorByOffice = ref<string | null>(null)

async function loadByOffice() {
  loadingByOffice.value = true
  errorByOffice.value = null
  byOfficeData.value = []

  try {
    const params: any = { date_from: dateFrom.value, date_to: dateTo.value }
    if (selectedArticle.value) params.article_id = selectedArticle.value

    const res = await api.get('/finance/by-office', { params, headers: authHeaders() })
    byOfficeData.value = res.data.offices
  } catch (err: any) {
    console.error(err)
    errorByOffice.value = err.response?.data?.message || 'Ошибка загрузки офисов'
  } finally {
    loadingByOffice.value = false
  }
}


// Загрузка офисов и статей
async function loadFilters() {
  try {
    const [officesRes, articlesRes] = await Promise.all([
      api.get('/offices', { headers: authHeaders() }),
      api.get('/articles', { headers: authHeaders() }),
    ])
    offices.value = officesRes.data
    articles.value = articlesRes.data
  } catch (err: any) {
    console.error(err)
    error.value = 'Ошибка при загрузке офисов или статей'
  }
}

// Загрузка динамики доходов/расходов
async function loadDynamics() {
  loading.value = true
  error.value = null
  chartData.value = null

  try {
    const params: any = { date_from: dateFrom.value, date_to: dateTo.value }
    if (selectedOffice.value) params.office_id = selectedOffice.value
    if (selectedArticle.value) params.article_id = selectedArticle.value

    const res = await api.get('/finance/dynamics', { params, headers: authHeaders() })
    const data = res.data

    const labels = Array.from(new Set([
      ...data.incomes.map((i: any) => i.date),
      ...data.expenses.map((e: any) => e.date),
    ])).sort()

    chartData.value = {
      labels,
      datasets: [
        {
          label: 'Доходы',
          data: labels.map(date => data.incomes.find((i: any) => i.date === date)?.total ?? 0),
          borderColor: 'rgb(34,197,94)',
          backgroundColor: 'rgba(34,197,94,0.2)',
        },
        {
          label: 'Расходы',
          data: labels.map(date => data.expenses.find((e: any) => e.date === date)?.total ?? 0),
          borderColor: 'rgb(239,68,68)',
          backgroundColor: 'rgba(239,68,68,0.2)',
        }
      ]
    }
  } catch (err: any) {
    console.error(err)
    error.value = err.response?.data?.message || 'Ошибка загрузки данных'
  } finally {
    loading.value = false
  }
}

// Загрузка баланса за период
async function loadBalance() {
  loadingBalance.value = true
  errorBalance.value = null
  balanceData.value = null

  try {
    const params: any = { date_from: dateFrom.value, date_to: dateTo.value }
    if (selectedOffice.value) params.office_id = selectedOffice.value
    if (selectedArticle.value) params.article_id = selectedArticle.value

    const res = await api.get('/finance/balance-period', { params, headers: authHeaders() })
    balanceData.value = res.data
  } catch (err: any) {
    console.error(err)
    errorBalance.value = err.response?.data?.message || 'Ошибка загрузки баланса'
  } finally {
    loadingBalance.value = false
  }
}

// Загружаем всё
async function loadAllData() {
  await Promise.all([loadDynamics(), loadBalance(), loadByOffice()])
}

// При монтировании
onMounted(async () => {
  await loadFilters()
  await loadAllData()
})
</script>
