import './assets/tailwind.css';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './App.vue';
import { getCsrf } from './axios';
import AlertModal from './components/AlertModal.vue'

const app = createApp(App);
app.use(createPinia());
app.use(router);

getCsrf()
  .then(() => console.log('CSRF cookie получен'))
  .catch((err) => console.error('Ошибка CSRF', err));

app.component('AlertModal', AlertModal)
app.mount('#app');
