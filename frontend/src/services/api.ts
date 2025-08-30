import axios from 'axios'
import { useUserStore } from '../stores/userStore'

// Используем переменную окружения для Docker / локальной разработки
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:9000/api'

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
})

// Интерцептор для автоматического подставления токена
api.interceptors.request.use((config) => {
  const userStore = useUserStore()
  const token = userStore.token || localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

export default api
