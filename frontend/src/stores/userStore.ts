import { defineStore } from 'pinia'
import api from '@/axios'

export const useUserStore = defineStore('user', {
  state: () => ({
    token: '' as string,
    user: null as null | any,
    isAuthChecked: false, // чтобы знать, что проверка токена завершена
  }),
  actions: {
    async login(email: string, password: string) {
      const res = await api.post('/login', { email, password })
      // Правильно берём данные из вложенного объекта
      this.token = res.data.data.token
      this.user = res.data.data.user
      localStorage.setItem('token', this.token)
    },
    async logout() {
      if (this.token) {
        try {
          await api.post('/logout', {}, { headers: { Authorization: `Bearer ${this.token}` } })
        } catch {}
      }
      this.token = ''
      this.user = null
      localStorage.removeItem('token')
    },
    async register(name: string, email: string, password: string, passwordConfirmation: string) {
      await api.post('/register', {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      })
      // Ничего не сохраняем в state, токен придёт только при логине
    },

    async checkToken() {
      if (this.isAuthChecked) return // предотвращает повторные запросы

      const token = localStorage.getItem('token')
      if (!token) throw new Error('Нет токена')

      try {
        const res = await api.get('/current', { headers: { Authorization: `Bearer ${token}` } })
        this.user = res.data.data.user
        this.token = token
        this.isAuthChecked = true // обязательно выставить после успешного запроса
      } catch {
        await this.logout()
        throw new Error('Токен недействителен')
      }
    },
  },
})
