<template>
  <div class="w-full max-w-5xl p-4 bg-white shadow rounded">
    <h3 class="text-xl font-bold mb-4">Приходы</h3>

    <!-- Таблица доходов -->
    <table class="w-full table-auto border">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-2 py-1">Описание</th>
          <th class="border px-2 py-1">Сумма</th>
          <th class="border px-2 py-1">Статья</th>
          <th class="border px-2 py-1">Действия</th>
        </tr>

        <!-- Инлайновая форма для добавления -->
        <tr class="bg-gray-100">
          <td class="border px-2 py-1">
            <input
              v-model="form.description"
              class="w-full border px-2 py-1 rounded"
              placeholder="Описание"
            />
          </td>
          <td class="border px-2 py-1">
            <input
              v-model.number="form.amount"
              type="number"
              class="w-full border px-2 py-1 rounded"
              placeholder="Сумма"
            />
          </td>
          <td class="border px-2 py-1">
            <select v-model="form.article_id" class="w-full border px-2 py-1 rounded">
              <option disabled value="">Выберите статью</option>
              <option
                v-for="article in articles"
                :key="article.id"
                :value="article.id"
              >
                {{ article.name }}
              </option>
            </select>
          </td>
          <td class="border px-2 py-1">
            <button @click="saveIncome" class="px-3 py-1 bg-green-500 text-white rounded">
              Добавить
            </button>
          </td>
        </tr>
      </thead>

      <tbody>
        <tr v-for="income in incomes" :key="income.id">
          <td class="border px-2 py-1">{{ income.description }}</td>
          <td class="border px-2 py-1">{{ income.amount }}</td>
          <td class="border px-2 py-1">{{ getArticleName(income.article_id) }}</td>
          <td class="border px-2 py-1">
            <button
              @click="deleteIncome(income.id)"
              class="px-2 py-1 bg-red-500 text-white rounded"
            >
              Удалить
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()
const props = defineProps<{ officeId: number | null }>()

// Основные состояния
const incomes = ref<any[]>([])
const articles = ref<any[]>([])

const form = ref({
  description: '',
  amount: 0,
  article_id: '',
})

// Заголовки с токеном
const authHeaders = () => ({
  headers: { Authorization: `Bearer ${userStore.token}` },
})

// Получение доходов
const fetchIncomes = async () => {
  try {
    const res = props.officeId
      ? await api.get(`/incomes/${props.officeId}`, authHeaders())
      : await api.get('/incomes', authHeaders())

    // В API возвращаем всегда data
    incomes.value = res.data.data || res.data
  } catch (err) {
    console.error(err)
    incomes.value = []
  }
}

// Получение статей (общие для всех)
const fetchArticles = async () => {
  try {
    const res = await api.get('/articles', authHeaders())
    articles.value = Array.isArray(res.data.data) ? res.data.data : res.data
  } catch (err) {
    console.error(err)
    articles.value = []
  }
}

// Получение названия статьи по id
const getArticleName = (id: number | string) => {
  const article = articles.value.find(a => a.id == id)
  return article ? article.name : '-'
}

// Создание дохода
const saveIncome = async () => {
  if (!form.value.description || !form.value.amount || !form.value.article_id) {
    alert('Заполните все поля!')
    return
  }

  try {
    await api.post(
      '/incomes',
      { ...form.value, office_id: props.officeId },
      authHeaders()
    )
    form.value = { description: '', amount: 0, article_id: '' }
    fetchIncomes()
  } catch (err) {
    console.error(err)
    alert('Ошибка при добавлении прихода')
  }
}

// Удаление дохода
const deleteIncome = async (id: number) => {
  try {
    await api.delete(`/incomes/${id}`, authHeaders())
    fetchIncomes()
  } catch (err) {
    console.error(err)
    alert('Ошибка при удалении')
  }
}

// Перезагрузка данных при смене офиса
watch(
  () => props.officeId,
  () => {
    fetchIncomes()
    fetchArticles()
  },
  { immediate: true }
)

// Начальная загрузка
onMounted(() => {
  fetchIncomes()
  fetchArticles()
})
</script>
