import { defineStore } from 'pinia'
import api from '@/axios'

export const useUserStore = defineStore('user', {
  state: () => ({
    token: localStorage.getItem('token') || '',
    user: null as null | any,
  }),
  actions: {
    async login(email: string, password: string) {
      await api.get('http://localhost:9000/sanctum/csrf-cookie', { withCredentials: true })
      const res = await api.post('/login', { email, password })
      this.token = res.data.token
      localStorage.setItem('token', this.token)
      this.user = res.data.user
    },

    async register(name: string, email: string, password: string, password_confirmation: string) {
      await api.post('/register', { name, email, password, password_confirmation })
    },

    async logout() {
      if (this.token) {
        await api.post('/logout', {}, { headers: { Authorization: `Bearer ${this.token}` } })
      }
      this.token = ''
      this.user = null
      localStorage.removeItem('token')
    },

    async checkToken() {
      if (!this.token) throw new Error('Нет токена')
      try {
        const res = await api.get('/current', { headers: { Authorization: `Bearer ${this.token}` } })
        this.user = res.data
      } catch {
        this.logout()
        throw new Error('Токен недействителен')
      }
    },
  },
})
