import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '@/stores/userStore'
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import Dashboard from '@/views/Dashboard.vue'

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

  if (!userStore.isAuthChecked && userStore.token) {
    try {
      await userStore.checkToken()
    } catch { }
  }

  if (to.meta.requiresAuth && !userStore.token) {
    return next('/login')
  }

  if ((to.path === '/login' || to.path === '/register') && userStore.token) {
    return next('/dashboard')
  }

  next()
})


export default router
