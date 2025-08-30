import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import Dashboard from '@/views/Dashboard.vue'
import { useUserStore } from '@/stores/userStore'

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

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore()

  if (to.meta.requiresAuth) {
    if (!userStore.token) return next('/login')

    try {
      await userStore.checkToken() // проверка токена на сервере
      next()
    } catch {
      next('/login')
    }
  } else if ((to.path === '/login' || to.path === '/register') && userStore.token) {
    next('/dashboard')
  } else {
    next()
  }
})

export default router
