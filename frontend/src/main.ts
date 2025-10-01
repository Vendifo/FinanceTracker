import './assets/tailwind.css';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './App.vue';
import { useUserStore } from './stores/userStore';
import AlertModal from './components/AlertModal.vue';
import { getCsrf } from './axios';

const app = createApp(App);
app.use(createPinia());
app.use(router);

app.component('AlertModal', AlertModal);
app.mount('#app');

// получаем CSRF cookie сразу после старта
getCsrf()
  .then(() => console.log('CSRF cookie получен'))
  .catch((err) => console.error('Ошибка CSRF', err));

// восстановление пользователя при старте приложения
const userStore = useUserStore();
userStore.checkAuth();
