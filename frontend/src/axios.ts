import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:9000/api', // твой бэкенд
  withCredentials: true, // нужно для cookie Sanctum
})

// Получение CSRF cookie (если используешь Sanctum)
export const getCsrf = () => axios.get('http://localhost:9000/sanctum/csrf-cookie', { withCredentials: true })

export default api
