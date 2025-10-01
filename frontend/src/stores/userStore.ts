import { defineStore } from 'pinia';
import api, { getCsrf } from '../axios';

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null as null | any,
    isAuthChecked: false,
  }),
  getters: {
    isAuthenticated: (state) => !!state.user,
  },
  actions: {
    // регистрация
    async register(name: string, email: string, password: string, passwordConfirmation: string) {
      await getCsrf();
      await api.post('/register', {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });
      // после регистрации сразу логинимся
      await this.login(email, password);
    },

    // вход
    async login(email: string, password: string) {
      await getCsrf(); // CSRF перед login
      await api.post('/login', { email, password });
      await this.fetchUser(); // получаем пользователя
      this.isAuthChecked = true;
    },

    // получение текущего пользователя
    async fetchUser() {
      try {
        const res = await api.get('/current'); // /me возвращает user из Laravel
        this.user = res.data.data.user;
      } catch (err) {
        console.error('Не удалось получить пользователя', err);
        this.user = null;
      }
    },

    // проверка сессии при старте приложения
    async checkAuth() {
      if (this.isAuthChecked) return;
      await this.fetchUser();
      this.isAuthChecked = true;
    },

    // выход
    async logout() {
      try {
        await api.post('/logout');
      } catch (err) {
        console.error('Ошибка при logout', err);
      }
      this.user = null;
    },
  },
});
