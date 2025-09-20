<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header />

    <main class="flex-1 p-6 w-full max-w-7xl mx-auto">
      <div class="bg-white text-gray-800 rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Расширенный поиск</h2>

        <!-- Выбор типа: расход или доход -->
        <div  class="mb-4 flex gap-4">
          <button :class="type === 'expense' ? 'bg-blue-600 text-white' : 'bg-gray-200'"
            @click.prevent="setType('expense')" class="px-4 py-2 rounded-lg">
            Расходы
          </button>
          <button :class="type === 'income' ? 'bg-blue-600 text-white' : 'bg-gray-200'" @click.prevent="setType('income')"
            class="px-4 py-2 rounded-lg">
            Доходы
          </button>
        </div>

        <!-- Фильтры -->
        <form @submit.prevent="search" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
          <input v-model="filters.description" type="text" placeholder="Описание" class="input" />
          <input v-model.number="filters.amount_min" type="number" placeholder="Сумма от" class="input" />
          <input v-model.number="filters.amount_max" type="number" placeholder="Сумма до" class="input" />
          <input v-model="filters.date_from" type="date" placeholder="Дата от" class="input" />
          <input v-model="filters.date_to" type="date" placeholder="Дата до" class="input" />

          <!-- Пользователь (только для расходов) -->
          <div v-if="type === 'expense'" class="relative">
            <input list="users-list" v-model="filters.user_name" placeholder="Пользователь" class="input" />
            <datalist id="users-list">
              <option v-for="user in users" :key="user.id" :value="user.name" />
            </datalist>
          </div>

          <!-- Статья -->
          <div class="relative">
            <input list="articles-list" v-model="filters.article_name" placeholder="Статья" class="input" />
            <datalist id="articles-list">
              <option v-for="article in articles" :key="article.id" :value="article.name" />
            </datalist>
          </div>

          <!-- Офис -->
          <div class="relative">
            <input list="offices-list" v-model="filters.office_name" placeholder="Офис" class="input" />
            <datalist id="offices-list">
              <option v-for="office in offices" :key="office.id" :value="office.name" />
            </datalist>
          </div>

          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Поиск
          </button>
        </form>

        <!-- Таблица результатов -->
        <div class="overflow-x-auto">
          <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-50 text-gray-600 text-sm font-medium border-b border-gray-300">
              <tr>
                <th class="px-4 py-2 text-left">ID</th>
                <th class="px-4 py-2 text-left">Описание</th>
                <th class="px-4 py-2 text-left">Сумма</th>
                <th v-if="type === 'expense'" class="px-4 py-2 text-left">Пользователь</th>
                <th class="px-4 py-2 text-left">Статья</th>
                <th class="px-4 py-2 text-left">Офис</th>
                <th class="px-4 py-2 text-left">Дата</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in results" :key="item.id"
                class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition border-b border-gray-200">
                <td class="px-4 py-3 font-medium text-gray-900">{{ item.id }}</td>
                <td class="px-4 py-3 text-gray-700">{{ item.description }}</td>
                <td class="px-4 py-3 text-gray-700">{{ item.amount }}</td>
                <td v-if="type === 'expense'" class="px-4 py-3 text-gray-700">{{ getUserName(item.user_id) }}</td>
                <td class="px-4 py-3 text-gray-700">{{ getArticleName(item.article_id) }}</td>
                <td class="px-4 py-3 text-gray-700">{{ getOfficeName(item.office_id) }}</td>
                <td class="px-4 py-3 text-gray-700">{{ formatDate(item.created_at) }}</td>

              </tr>
              <tr v-if="loading">
                <td :colspan="type === 'expense' ? 7 : 6"
                  class="px-4 py-3 text-gray-500 text-center border-b border-gray-200">
                  Загрузка...
                </td>
              </tr>
              <tr v-if="error">
                <td :colspan="type === 'expense' ? 7 : 6"
                  class="px-4 py-3 text-red-500 text-center border-b border-gray-200">
                  {{ error }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'
import { useAlert } from '@/composables/useAlert'
import Header from '@/components/Header.vue'

const userStore = useUserStore()
const { showAlert } = useAlert()

type ItemType = 'expense' | 'income'

interface Item {
  id: number
  description: string
  amount: string
  user_id?: number
  article_id: number
  office_id: number
  created_at: string
}

interface ResponseData {
  data: Item[]
}

interface User { id: number; name: string }
interface Article { id: number; name: string }
interface Office { id: number; name: string }

const type = ref<ItemType>('expense')
const filters = ref({
  description: '',
  amount_min: null as number | null,
  amount_max: null as number | null,
  date_from: '',
  date_to: '',
  user_name: '',
  article_name: '',
  office_name: '',
})

const results = ref<Item[]>([])
const users = ref<User[]>([])
const articles = ref<Article[]>([])
const offices = ref<Office[]>([])
const loading = ref(false)
const error = ref<string | null>(null)

// Сброс фильтров
const resetFilters = () => {
  filters.value = {
    description: '',
    amount_min: null,
    amount_max: null,
    date_from: '',
    date_to: '',
    user_name: '',
    article_name: '',
    office_name: '',
  }
  results.value = [] // Очистить результаты поиска
}

// При смене типа
const setType = (newType: ItemType) => {
  if (type.value !== newType) {
    type.value = newType
    resetFilters()
  }
}

const authHeaders = () => ({ Authorization: `Bearer ${userStore.token}` })

// Загрузка справочников
const fetchLookups = async () => {
  try {
    const [resUsers, resArticles, resOffices] = await Promise.all([
      api.get('/users', { headers: authHeaders() }),
      api.get('/articles', { headers: authHeaders() }),
      api.get('/offices', { headers: authHeaders() }),
    ])
    users.value = resUsers.data.data
    articles.value = resArticles.data.data
    offices.value = resOffices.data.data || resOffices.data
  } catch (err: any) {
    console.error(err)
    showAlert({ type: 'error', title: 'Ошибка', message: 'Не удалось загрузить справочники' })
  }
}

const formatDate = (iso: string) => {
  if (!iso) return '-'
  const date = new Date(iso)
  const day = String(date.getDate()).padStart(2, '0')
  const month = String(date.getMonth() + 1).padStart(2, '0') // Месяцы с 0
  const year = date.getFullYear()
  return `${year}-${month}-${day}` // Формат YYYY-MM-DD
}


onMounted(() => fetchLookups())

// Поиск
const search = async () => {
  loading.value = true
  error.value = null

  try {
    const endpoint = type.value === 'expense' ? '/expenses/search' : '/incomes/search'

    // Преобразуем имена в id для фильтров
    const params: any = { ...filters.value }
    if (filters.value.user_name) {
      const user = users.value.find(u => u.name === filters.value.user_name)
      if (user) params.user_id = user.id
    }
    if (filters.value.article_name) {
      const article = articles.value.find(a => a.name === filters.value.article_name)
      if (article) params.article_id = article.id
    }
    if (filters.value.office_name) {
      const office = offices.value.find(o => o.name === filters.value.office_name)
      if (office) params.office_id = office.id
    }

    const { data } = await api.get<ResponseData>(endpoint, {
      params,
      headers: authHeaders(),
    })
    results.value = data.data
  } catch (err: any) {
    console.error(err)
    error.value = err.response?.data?.message || 'Ошибка поиска'
    showAlert({ type: 'error', title: 'Ошибка', message: error.value || 'Ошибка поиска' })
  } finally {
    loading.value = false
  }
}

// Получение названий по ID
const getUserName = (id?: number) => users.value.find(u => u.id === id)?.name || '-'
const getArticleName = (id: number) => articles.value.find(a => a.id === id)?.name || '-'
const getOfficeName = (id: number) => offices.value.find(o => o.id === id)?.name || '-'
</script>

<style scoped>
.input {
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  width: 100%;
}
</style>
