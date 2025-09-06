<template>
  <div class="w-full min-h-screen flex flex-col bg-gray-50 p-6">
    <!-- Фильтры -->
    <div class="flex gap-4 mb-6 flex-wrap items-end">
      <div>
        <label class="block mb-1 font-semibold">Офис</label>
        <select v-model="selectedOfficeId" class="border px-2 py-1 rounded w-full">
          <option :value="null">Все офисы</option>
          <option v-for="office in offices" :key="office.id" :value="office.id">
            {{ office.name }}
          </option>
        </select>
      </div>

      <div>
        <label class="block mb-1 font-semibold">До даты</label>
        <input type="date" v-model="filterDate" class="border px-2 py-1 rounded" />
      </div>

      <div class="ml-auto text-lg font-bold">
        Баланс: {{ balance }}
      </div>
    </div>

    <!-- Таблицы доходов и расходов -->
    <div class="flex gap-6 flex-wrap">
      <div class="flex-1 min-w-[400px]">
        <IncomeTable
          :incomes="incomes"
          :articles="articles"
          :office-id="selectedOfficeId"
          :filter-date="filterDate"
          @refresh="fetchData"
        />
      </div>
      <div class="flex-1 min-w-[400px]">
        <ExpenseTable
          :expenses="expenses"
          :articles="articles"
          :users="users"
          :office-id="selectedOfficeId"
          :filter-date="filterDate"
          @refresh="fetchData"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import api from '@/axios';
import IncomeTable from '@/components/IncomeTable.vue';
import ExpenseTable from '@/components/ExpenseTable.vue';
import { useUserStore } from '@/stores/userStore';

const userStore = useUserStore();

const offices = ref<any[]>([]);
const selectedOfficeId = ref<number | null>(null);
const filterDate = ref<string>(new Date().toISOString().slice(0, 10));

const users = ref<any[]>([]);
const incomes = ref<any[]>([]);
const expenses = ref<any[]>([]);
const articles = ref<any[]>([]);
const balance = ref<number>(0);

const authHeaders = () => ({
  Authorization: `Bearer ${userStore.token}`,
});

const fetchData = async () => {
  try {
    const [officeRes, articlesRes, usersRes, financeRes] = await Promise.all([
      api.get('/offices', { headers: authHeaders() }),
      api.get('/articles', { headers: authHeaders() }),
      api.get('/users', { headers: authHeaders() }),
      api.get('/finance', {
        params: { office_id: selectedOfficeId.value, date: filterDate.value },
        headers: authHeaders(),
      }),
    ]);

    offices.value = officeRes.data.data || officeRes.data;
    articles.value = articlesRes.data.data || articlesRes.data;
    users.value = usersRes.data.data || usersRes.data;

    incomes.value = financeRes.data.incomes ?? [];
    expenses.value = financeRes.data.expenses ?? [];
    balance.value = financeRes.data.balance ?? 0;
  } catch (err) {
    console.error(err);
    incomes.value = [];
    expenses.value = [];
    articles.value = [];
    users.value = [];
    balance.value = 0;
  }
};

// Загрузка данных сразу один раз и при изменении фильтров
watch([selectedOfficeId, filterDate], fetchData, { immediate: true });
</script>
