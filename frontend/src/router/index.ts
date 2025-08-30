import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Dashboard from '../views/Dashboard.vue'
import { useUserStore } from '../stores/userStore'

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Навигационный guard
router.beforeEach((to, from, next) => {
  const userStore = useUserStore()

  // Если маршрут защищён и нет токена → редирект на /login
  if (to.meta.requiresAuth && !userStore.token) {
    next('/login')
  } else if ((to.path === '/login' || to.path === '/register') && userStore.token) {
    // Если пользователь уже авторизован → редирект на Dashboard
    next('/dashboard')
  } else {
    next()
  }
})

export default router
