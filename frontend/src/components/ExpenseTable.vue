<template>
  <div class="bg-white shadow rounded p-4">
    <h3 class="text-xl font-bold mb-4">Расходы</h3>
    <table class="w-full table-auto border">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-2 py-1">Описание</th>
          <th class="border px-2 py-1">Сумма</th>
          <th class="border px-2 py-1">Статья</th>
          <th class="border px-2 py-1">Пользователь</th>
          <th class="border px-2 py-1">Действия</th>
        </tr>

        <!-- Форма добавления -->
        <tr class="bg-gray-100">
          <td class="border px-2 py-1">
            <input v-model="form.description" placeholder="Описание" class="w-full border px-2 py-1 rounded"/>
          </td>
          <td class="border px-2 py-1">
            <input v-model.number="form.amount" type="number" placeholder="Сумма" class="w-full border px-2 py-1 rounded"/>
          </td>
          <td class="border px-2 py-1">
            <select v-model="form.article_id" class="w-full border px-2 py-1 rounded">
              <option disabled value="">Выберите статью</option>
              <option v-for="article in articles" :key="article.id" :value="article.id">{{ article.name }}</option>
            </select>
          </td>
          <td class="border px-2 py-1">
            <select v-model="form.user_id" class="w-full border px-2 py-1 rounded">
              <option disabled value="">Выберите пользователя</option>
              <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
            </select>
          </td>
          <td class="border px-2 py-1">
            <button @click="saveExpense" class="px-3 py-1 bg-green-500 text-white rounded">Добавить</button>
          </td>
        </tr>
      </thead>

      <tbody>
        <tr v-for="expense in expenses" :key="expense.id">
          <td class="border px-2 py-1">{{ expense.description }}</td>
          <td class="border px-2 py-1">{{ expense.amount }}</td>
          <td class="border px-2 py-1">{{ getArticleName(expense.article_id) }}</td>
          <td class="border px-2 py-1">{{ getUserName(expense.user_id) }}</td>
          <td class="border px-2 py-1">
            <button @click="deleteExpense(expense.id)" class="px-2 py-1 bg-red-500 text-white rounded">Удалить</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'

const userStore = useUserStore()

const props = defineProps<{
  expenses: any[],
  articles: any[],
  users: any[],
  officeId: number | null,
  filterDate: string
}>()
const emits = defineEmits(['refresh'])

const form = ref({ description: '', amount: 0, article_id: '', user_id: '' })

// Заголовки с токеном
const authHeaders = () => ({ Authorization: `Bearer ${userStore.token}` })

const getArticleName = (id: number) => props.articles.find(a => a.id == id)?.name ?? '-'
const getUserName = (id: number) => props.users.find(u => u.id == id)?.name ?? '-'

const saveExpense = async () => {
  if (!form.value.description || !form.value.amount || !form.value.article_id || !form.value.user_id) {
    return alert('Заполните все поля!');
  }
  if (!props.officeId) return alert('Выберите офис для добавления расхода!');

  try {
    await api.post('/expenses', {
      ...form.value,
      office_id: props.officeId,
      created_at: props.filterDate || new Date().toISOString().slice(0, 10) // дата из фильтра
    }, { headers: authHeaders() });

    form.value = { description: '', amount: 0, article_id: '', user_id: '' };
    emits('refresh');
  } catch (err) {
    console.error(err);
    alert('Ошибка при добавлении');
  }
};


const deleteExpense = async (id: number) => {
  try {
    await api.delete(`/expenses/${id}`, { headers: authHeaders() })
    emits('refresh')
  } catch (err) {
    console.error(err)
    alert('Ошибка при удалении')
  }
}
</script>
