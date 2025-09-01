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
        } catch { }
      }
      this.token = ''
      this.user = null
      localStorage.removeItem('token')
    },

    async checkToken() {
      const token = localStorage.getItem('token')
      if (!token) throw new Error('Нет токена')

      try {
        const res = await api.get('/current', { headers: { Authorization: `Bearer ${token}` } })
        this.user = res.data.data.user // если сервер возвращает в data.user
        this.token = token
      } catch {
        await this.logout()
        throw new Error('Токен недействителен')
      }
    },
  },
})
