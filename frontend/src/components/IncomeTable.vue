<template>
  <div class="bg-white shadow rounded p-4">
    <h3 class="text-xl font-bold mb-4">Приходы</h3>
    <table class="w-full table-auto border">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-2 py-1">Описание</th>
          <th class="border px-2 py-1">Сумма</th>
          <th class="border px-2 py-1">Статья</th>
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
            <button @click="saveIncome" class="px-3 py-1 bg-green-500 text-white rounded">Добавить</button>
          </td>
        </tr>
      </thead>

      <tbody>
        <tr v-for="income in incomes" :key="income.id">
          <td class="border px-2 py-1">{{ income.description }}</td>
          <td class="border px-2 py-1">{{ income.amount }}</td>
          <td class="border px-2 py-1">{{ getArticleName(income.article_id) }}</td>
          <td class="border px-2 py-1">
            <button @click="deleteIncome(income.id)" class="px-2 py-1 bg-red-500 text-white rounded">Удалить</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import api from '@/axios';
import { useUserStore } from '@/stores/userStore';

const userStore = useUserStore();

const props = defineProps<{ incomes: any[], articles: any[], officeId: number | null, filterDate: string }>();
const emits = defineEmits(['refresh']);

const form = ref({ description: '', amount: 0, article_id: '' });

const authHeaders = () => ({
  Authorization: `Bearer ${userStore.token}`
});

const getArticleName = (id: number) => {
  const article = props.articles.find(a => a.id === id);
  return article ? article.name : '-';
}

const saveIncome = async () => {
  if (!form.value.description || !form.value.amount || !form.value.article_id) return alert('Заполните все поля!');
  if (!props.officeId) return alert('Выберите офис!');

  try {
    await api.post('/incomes', {
      ...form.value,
      office_id: props.officeId,
      created_at: props.filterDate || new Date().toISOString().slice(0, 10)
    }, { headers: authHeaders() });

    form.value = { description: '', amount: 0, article_id: '' };
    emits('refresh');
  } catch (err) {
    console.error(err);
    alert('Ошибка при добавлении');
  }
};

const deleteIncome = async (id: number) => {
  try {
    await api.delete(`/incomes/${id}`, { headers: authHeaders() });
    emits('refresh');
  } catch (err) {
    console.error(err);
    alert('Ошибка при удалении');
  }
};
</script>
