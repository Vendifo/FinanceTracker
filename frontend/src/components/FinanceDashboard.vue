<template>
  <div class="w-full min-h-screen flex flex-col bg-gray-50 p-6">
    <!-- Фильтры -->
    <div class="flex gap-4 mb-6 flex-wrap items-end">
      <div class="flex flex-wrap gap-4 items-end">
        <!-- Фильтр по офису -->
        <div class="flex flex-col w-60">
          <label class="mb-1 font-semibold text-gray-700">Офис</label>
          <div class="relative">
            <select v-model="selectedOfficeId"
              class="appearance-none w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
              <option :value="null">Все офисы</option>
              <option v-for="office in offices" :key="office.id" :value="office.id">
                {{ office.name }}
              </option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Фильтр по дате -->
        <div class="flex flex-col w-52">
          <label class="mb-1 font-semibold text-gray-700">До даты</label>
          <input type="date" v-model="filterDate"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white" />
        </div>
      </div>


     <div class="ml-auto text-lg font-bold">
  Баланс: {{ balance.toLocaleString('ru-RU', { maximumFractionDigits: 0 }) }} ₽
</div>


    </div>

    <!-- Таблицы доходов и расходов -->
    <div class="flex gap-6 flex-wrap">
      <div class="flex-1 min-w-[400px]">
        <IncomeTable :incomes="incomes" :articles="articles" :office-id="selectedOfficeId" :filter-date="filterDate"
          @refresh="fetchData" />
      </div>
      <div class="flex-1 min-w-[400px]">
        <ExpenseTable :expenses="expenses" :articles="articles" :users="users" :office-id="selectedOfficeId"
          :filter-date="filterDate" @refresh="fetchData" />
      </div>
    </div>
  </div>
</template>


<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import api from '@/axios';
import IncomeTable from '@/components/IncomeTable.vue';
import ExpenseTable from '@/components/ExpenseTable.vue';
import { useUserStore } from '@/stores/userStore';
import { useAlert } from '@/composables/useAlert';

const userStore = useUserStore();
const { showAlert } = useAlert();

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

// Загружаем офисы пользователя
const fetchUserOffices = async () => {
  try {
    const res = await api.get(`/users/${userStore.user.id}/offices`, {
      headers: authHeaders(),
    });
    offices.value = res.data.data || res.data;

    // получаем активный офис
    const currentOffice = await api.get('/current-office', {
      headers: authHeaders(),
    });
    selectedOfficeId.value = currentOffice.data?.id ?? null;
  } catch (err: any) {
    console.error(err);
    offices.value = [];
    showAlert({
      type: 'error',
      title: 'Ошибка',
      message: 'Не удалось загрузить список офисов',
    });
  }
};

// Переключение активного офиса
const switchOffice = async (officeId: number | null) => {
  try {
    await api.post(
      '/switch-office',
      { office_id: officeId },
      { headers: authHeaders() }
    );
    await fetchData();
  } catch (err: any) {
    console.error(err);
    showAlert({
      type: 'error',
      title: 'Ошибка',
      message: 'Не удалось переключить офис',
    });
  }
};

// Получение данных
const fetchData = async () => {
  if (!selectedOfficeId.value) {
    incomes.value = [];
    expenses.value = [];
    articles.value = [];
    users.value = [];
    balance.value = 0;
    return;
  }

  try {
    const [articlesRes, usersRes, financeRes] = await Promise.all([
      api.get('/articles', { headers: authHeaders() }),
      api.get('/users', { headers: authHeaders() }),
      api.get('/finance', {
        params: { office_id: selectedOfficeId.value, date: filterDate.value },
        headers: authHeaders(),
      }),
    ]);

    articles.value = articlesRes.data.data || articlesRes.data;
    users.value = usersRes.data.data || usersRes.data;
    incomes.value = financeRes.data.incomes ?? [];
    expenses.value = financeRes.data.expenses ?? [];
    balance.value = financeRes.data.balance ?? 0;
  } catch (err: any) {
    console.error(err);
    incomes.value = [];
    expenses.value = [];
    articles.value = [];
    users.value = [];
    balance.value = 0;
    showAlert({
      type: 'error',
      title: 'Ошибка',
      message:
        err.response?.data?.message ||
        'Не удалось загрузить данные финансового дашборда',
    });
  }
};

// Следим за изменением выбранного офиса
watch(selectedOfficeId, (officeId) => {
  if (officeId) {
    switchOffice(officeId);
  } else {
    // если выбран "Все офисы", то ничего не показываем
    incomes.value = [];
    expenses.value = [];
    balance.value = 0;
  }
});

// Следим за датой
watch(filterDate, fetchData);

// Загружаем офисы и текущий офис при монтировании
onMounted(fetchUserOffices);
</script>
