import { createRouter, createWebHistory } from 'vue-router'

// Пустой стартовый маршрут на главную страницу
const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('../App.vue'),
  },
]

// Создание экземпляра роутера
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router
