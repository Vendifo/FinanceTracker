import './assets/tailwind.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'
import { getCsrf } from './axios'

const app = createApp(App)
app.use(createPinia())
app.use(router)

// Получаем CSRF один раз при старте приложения
getCsrf()
  .then(() => console.log('CSRF cookie получен'))
  .catch(err => console.error('Ошибка CSRF', err))

app.mount('#app')
