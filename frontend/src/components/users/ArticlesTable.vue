<template>
  <div class="bg-white text-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-2xl font-semibold">Статьи</h3>
      <button
        @click="openAddArticle"
        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
      >
        Добавить статью
      </button>
    </div>

    <!-- Состояния -->
    <div v-if="loading" class="text-gray-500 mb-2">Загрузка...</div>
    <div v-if="error" class="text-red-500 mb-2">{{ error }}</div>

    <!-- Таблица -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50 text-gray-600 text-sm font-medium">
          <tr>
            <th class="px-4 py-2 text-left">Название</th>
            <th class="px-4 py-2 text-left">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="article in articles"
            :key="article.id"
            class="hover:bg-gray-50 transition"
          >
            <td class="px-4 py-3 text-gray-700">{{ article.name }}</td>
            <td class="px-4 py-3 flex gap-2">
              <button
                @click="openEditArticle(article)"
                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition"
              >
                Редактировать
              </button>
              <button
                @click="openDeleteArticle(article)"
                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition"
              >
                Удалить
              </button>
            </td>
          </tr>
          <tr v-if="articles.length === 0">
            <td colspan="2" class="text-center py-4">Статей нет</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Модалка добавления -->
    <div v-if="addArticleModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-white/30 backdrop-blur-sm" @click="closeAddArticle"></div>
      <div class="relative bg-white p-6 rounded-2xl shadow-xl w-96 z-10" @click.stop>
        <h3 class="text-lg font-bold mb-4">Новая статья</h3>
        <input
          v-model="articleForm.name"
          type="text"
          placeholder="Название статьи"
          class="border px-3 py-2 rounded w-full mb-4 focus:outline-none focus:ring-2 focus:ring-green-500"
        />
        <div class="flex justify-end gap-2">
          <button @click="closeAddArticle" class="px-3 py-1 border rounded">Отмена</button>
          <button
            @click="createArticleHandler"
            :disabled="actionLoading"
            class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition disabled:opacity-50"
          >
            {{ actionLoading ? 'Сохраняем...' : 'Создать' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Модалка редактирования -->
    <div v-if="editArticleModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-white/30 backdrop-blur-sm" @click="closeEdit"></div>
      <div class="relative bg-white p-6 rounded-2xl shadow-xl w-96 z-10" @click.stop>
        <h3 class="text-lg font-bold mb-4">Редактировать статью</h3>
        <input
          v-model="articleForm.name"
          type="text"
          placeholder="Название статьи"
          class="border px-3 py-2 rounded w-full mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
        <div class="flex justify-end gap-2">
          <button @click="closeEdit" class="px-3 py-1 border rounded">Отмена</button>
          <button
            @click="updateArticleHandler"
            :disabled="actionLoading"
            class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition disabled:opacity-50"
          >
            {{ actionLoading ? 'Сохраняем...' : 'Сохранить' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Модалка удаления -->
    <div v-if="deleteArticleModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-white/30 backdrop-blur-sm" @click="closeDelete"></div>
      <div class="relative bg-white p-6 rounded-2xl shadow-xl w-96 z-10" @click.stop>
        <h3 class="text-lg font-bold mb-4">Удалить статью?</h3>
        <p class="mb-4">Вы действительно хотите удалить статью <strong>{{ deleteArticleModal.name }}</strong>?</p>
        <div class="flex justify-end gap-2">
          <button @click="closeDelete" class="px-3 py-1 border rounded">Отмена</button>
          <button
            @click="deleteArticleHandler"
            :disabled="actionLoading"
            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition disabled:opacity-50"
          >
            {{ actionLoading ? 'Удаляем...' : 'Удалить' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'
import { useAlert } from '@/composables/useAlert'

const { showAlert } = useAlert()
const userStore = useUserStore()

const articles = ref<Array<{ id: number; name: string }>>([])
const loading = ref(false)
const error = ref<string | null>(null)
const actionLoading = ref(false)

const addArticleModal = ref(false)
const editArticleModal = ref<{ id: number; name: string } | null>(null)
const deleteArticleModal = ref<{ id: number; name: string } | null>(null)

const articleForm = ref<{ name: string }>({ name: '' })

const authHeaders = () => ({ Authorization: `Bearer ${userStore.token}` })

// Загрузка
const fetchArticles = async () => {
  loading.value = true
  try {
    const res = await api.get('/articles', { headers: authHeaders() })
    articles.value = Array.isArray(res.data) ? res.data : res.data.data || []
  } catch (err: any) {
    error.value = err.message || 'Ошибка загрузки'
    articles.value = []
  } finally {
    loading.value = false
  }
}
onMounted(fetchArticles)

// Открытие/закрытие модалок
const openAddArticle = () => { articleForm.value.name = ''; addArticleModal.value = true }
const closeAddArticle = () => (addArticleModal.value = false)

const openEditArticle = (article: { id: number; name: string }) => {
  editArticleModal.value = { ...article }
  articleForm.value.name = article.name
}
const closeEdit = () => { editArticleModal.value = null; articleForm.value.name = '' }

const openDeleteArticle = (article: { id: number; name: string }) => { deleteArticleModal.value = { ...article } }
const closeDelete = () => (deleteArticleModal.value = null)

// Добавление
const createArticleHandler = async () => {
  if (!articleForm.value.name) return showAlert({ type: 'warning', title: 'Ошибка', message: 'Введите название статьи' })
  try {
    actionLoading.value = true
    const res = await api.post('/articles', { name: articleForm.value.name }, { headers: authHeaders() })
    articles.value.push(res.data.data || res.data)
    showAlert({ type: 'success', title: 'Успех', message: 'Статья добавлена' })
    articleForm.value.name = ''
    closeAddArticle()
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  } finally { actionLoading.value = false }
}

// Обновление
const updateArticleHandler = async () => {
  if (!editArticleModal.value || !articleForm.value.name) return
  try {
    actionLoading.value = true
    const res = await api.put(`/articles/${editArticleModal.value.id}`, { name: articleForm.value.name }, { headers: authHeaders() })
    const index = articles.value.findIndex(a => a.id === editArticleModal.value!.id)
    if (index !== -1) articles.value[index] = res.data.data || res.data
    showAlert({ type: 'success', title: 'Успех', message: 'Статья обновлена' })
    closeEdit()
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  } finally { actionLoading.value = false }
}

// Удаление
const deleteArticleHandler = async () => {
  if (!deleteArticleModal.value) return
  try {
    actionLoading.value = true
    await api.delete(`/articles/${deleteArticleModal.value.id}`, { headers: authHeaders() })
    articles.value = articles.value.filter(a => a.id !== deleteArticleModal.value!.id)
    showAlert({ type: 'success', title: 'Успех', message: 'Статья удалена' })
    closeDelete()
  } catch (err: any) {
    showAlert({ type: 'error', title: 'Ошибка', message: err.response?.data?.message || err.message })
  } finally { actionLoading.value = false }
}
</script>
