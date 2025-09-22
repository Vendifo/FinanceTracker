<template>
  <div class="bg-white text-gray-800 rounded-lg shadow p-6">
    <h3 class="flex justify-between items-center text-2xl font-semibold mb-4">
  <span>Приходы</span>
  <span class="text-green-600 font-medium text-lg">
    Итого: {{ totalIncomes.toLocaleString('ru-RU', { maximumFractionDigits: 0 }) }} ₽
  </span>
</h3>


    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-50 text-gray-600 text-sm font-medium border-b border-gray-300">
          <tr>
            <th class="px-4 py-2 text-left">Описание</th>
            <th class="px-4 py-2 text-left">Сумма</th>
            <th class="px-4 py-2 text-left">Статья</th>
            <th class="px-4 py-2 text-left">Действия</th>
          </tr>

          <!-- Форма добавления -->
          <tr class="bg-gray-100 border-b border-gray-300">
            <td class="px-4 py-2">
              <input v-model="form.description" placeholder="Описание"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
            </td>
            <td class="px-4 py-2">
              <input v-model.number="form.amount" type="number" placeholder="Сумма"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
            </td>
            <td class="px-4 py-2">
              <select v-model="form.article_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option disabled value="">Выберите статью</option>
                <option v-for="article in articles" :key="article.id" :value="article.id">
                  {{ article.name }}
                </option>
              </select>
            </td>
            <td class="px-4 py-2">
              <button @click="saveIncome" :disabled="loading"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                <span v-if="loading">Сохраняем...</span>
                <span v-else>Добавить</span>
              </button>
            </td>
          </tr>
        </thead>

        <tbody>
          <tr v-for="income in incomes" :key="income.id"
            class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition border-b border-gray-200">
            <td class="px-4 py-3 font-medium text-gray-900">{{ income.description }}</td>
            <td class="px-4 py-3 text-gray-700">{{ income.amount }} ₽</td>
            <td class="px-4 py-3 text-gray-600">{{ getArticleName(income.article_id) }}</td>
            <td class="px-4 py-3">
              <button @click="deleteIncome(income.id)" :disabled="deletingIds.includes(income.id)"
                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                <span v-if="deletingIds.includes(income.id)">Удаляем...</span>
                <span v-else>Удалить</span>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>


<script setup lang="ts">
import { computed, ref } from 'vue'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'
import { useAlert } from '@/composables/useAlert'

const { showAlert } = useAlert()
const userStore = useUserStore()

const props = defineProps<{ incomes: any[], articles: any[], officeId: number | null, filterDate: string }>()
const emits = defineEmits(['refresh'])

// вычисляем общую сумму приходов
const totalIncomes = computed(() =>
  props.incomes.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0)
)

// форма и состояния загрузки
const form = ref({ description: '', amount: 0, article_id: '' })
const loading = ref(false)
const deletingIds = ref<number[]>([])

const authHeaders = () => ({
  Authorization: `Bearer ${userStore.token}`
})

const getArticleName = (id: number) => {
  const article = props.articles.find(a => a.id === id)
  return article ? article.name : '-'
}

// добавление прихода
const saveIncome = async () => {
  if (!form.value.description || !form.value.amount || !form.value.article_id) {
    return showAlert({ type: 'warning', title: 'Внимание', message: 'Заполните все поля!' })
  }
  if (!props.officeId) {
    return showAlert({ type: 'warning', title: 'Внимание', message: 'Выберите офис!' })
  }

  loading.value = true
  try {
    await api.post(
      '/incomes',
      {
        ...form.value,
        office_id: props.officeId,
        created_at: props.filterDate || new Date().toISOString().slice(0, 10)
      },
      { headers: authHeaders() }
    )

    form.value = { description: '', amount: 0, article_id: '' }
    showAlert({ type: 'success', title: 'Успех', message: 'Приход успешно добавлен!' })
    emits('refresh')
  } catch (err: any) {
    console.error(err)
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || 'Ошибка при добавлении' })
  } finally {
    loading.value = false
  }
}

// удаление прихода
const deleteIncome = async (id: number) => {
  deletingIds.value.push(id)
  try {
    await api.delete(`/incomes/${id}`, { headers: authHeaders() })
    showAlert({ type: 'success', title: 'Успех', message: 'Приход успешно удалён!' })
    emits('refresh')
  } catch (err: any) {
    console.error(err)
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || 'Ошибка при удалении' })
  } finally {
    deletingIds.value = deletingIds.value.filter(i => i !== id)
  }
}
</script>
