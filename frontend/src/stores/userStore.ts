import { defineStore } from 'pinia';
import api, { getCsrf } from '../axios';

export const useUserStore = defineStore('user', {
  state: () => ({
    token: localStorage.getItem('token') || '',
    user: null as null | any,
    isAuthChecked: false,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
  actions: {
    async login(email: string, password: string) {
      await getCsrf(); // CSRF перед логином
      const res = await api.post('/login', { email, password });
      this.token = res.data.data.token;
      this.user = res.data.data.user;
      localStorage.setItem('token', this.token);
      this.isAuthChecked = true;
    },

    async register(name: string, email: string, password: string, passwordConfirmation: string) {
      await getCsrf(); // CSRF перед регистрацией
      await api.post('/register', {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });
      // токен пока не сохраняем, будет через login
    },

    async checkToken() {
      if (this.isAuthChecked || !this.token) return;

      try {
        const res = await api.get('/current', { headers: { Authorization: `Bearer ${this.token}` } });
        this.user = res.data.data.user;
        this.isAuthChecked = true;
      } catch (err) {
        console.error('Проверка токена не удалась', err);
        await this.logout();
      }
    },

    async logout() {
      if (this.token) {
        try {
          await api.post('/logout', {}, { headers: { Authorization: `Bearer ${this.token}` } });
        } catch (err) {
          console.error('Ошибка при logout', err);
        }
      }
      this.token = '';
      this.user = null;
      this.isAuthChecked = true;
      localStorage.removeItem('token');
    },
  },
});
