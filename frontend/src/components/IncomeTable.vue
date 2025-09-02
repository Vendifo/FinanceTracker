<template>
  <div class="w-full max-w-5xl p-4 bg-white shadow rounded">
    <h3 class="text-xl font-bold mb-4">Приходы</h3>

    <table class="w-full table-auto border">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-2 py-1">Описание</th>
          <th class="border px-2 py-1">Сумма</th>
          <th class="border px-2 py-1">Статья</th>
          <th class="border px-2 py-1">Действия</th>
        </tr>
        <!-- Инлайновая форма -->
        <tr class="bg-gray-100">
          <td class="border px-2 py-1">
            <input v-model="form.description" class="w-full border px-2 py-1 rounded" placeholder="Описание" />
          </td>
          <td class="border px-2 py-1">
            <input v-model.number="form.amount" type="number" class="w-full border px-2 py-1 rounded" placeholder="Сумма" />
          </td>
          <td class="border px-2 py-1">
            <select v-model="form.article_id" class="w-full border px-2 py-1 rounded">
              <option value="" disabled selected>Выберите статью</option>
              <option v-for="article in articles" :key="article.id" :value="article.id">{{ article.title }}</option>
            </select>
          </td>
          <td class="border px-2 py-1">
            <button @click="saveIncome" class="px-3 py-1 bg-green-500 text-white rounded">Добавить</button>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="income in incomes" :key="income.id">
          <td class="border px-2 py-1">{{ income.description }}</td>
          <td class="border px-2 py-1">{{ income.amount }}</td>
          <td class="border px-2 py-1">{{ articles.find(a => a.id === income.article_id)?.title || '-' }}</td>
          <td class="border px-2 py-1">
            <button @click="deleteIncome(income.id)" class="px-2 py-1 bg-red-500 text-white rounded">Удалить</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()

const incomes = ref<any[]>([])
const articles = ref<any[]>([])

const form = ref({
  description: '',
  amount: 0,
  article_id: null,
})

// Заголовки с токеном
const authHeaders = () => ({
  headers: { Authorization: `Bearer ${userStore.token}` }
})

const fetchIncomes = async () => {
  const res = await api.get('/incomes', authHeaders())
  incomes.value = res.data.data
}

const fetchArticles = async () => {
  const res = await api.get('/articles', authHeaders())
  articles.value = res.data.data
}

const saveIncome = async () => {
  if (!form.value.description || !form.value.amount || !form.value.article_id) {
    alert('Заполните все поля!')
    return
  }
  await api.post('/incomes', form.value, authHeaders())
  form.value = { description: '', amount: 0, article_id: null }
  fetchIncomes()
}

const deleteIncome = async (id: number) => {
  if (confirm('Удалить приход?')) {
    await api.delete(`/incomes/${id}`, authHeaders())
    fetchIncomes()
  }
}

onMounted(() => {
  fetchIncomes()
  fetchArticles()
})
</script>
