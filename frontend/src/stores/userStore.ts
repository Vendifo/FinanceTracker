import { defineStore } from 'pinia'

interface User {
  id: number
  name: string
  email: string
}

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null as User | null,
    token: localStorage.getItem('token') || '',
  }),
  actions: {
    setUser(user: User, token: string) {
      this.user = user
      this.token = token
      localStorage.setItem('token', token)
    },
    logout() {
      this.user = null
      this.token = ''
      localStorage.removeItem('token')
    },
  },
})
