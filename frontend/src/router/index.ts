import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '@/stores/userStore'

// Страницы
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import Dashboard from '@/views/Dashboard.vue'
import Users from '@/views/Users.vue'
import Reports from '@/views/Reports.vue'

const routes = [
  // редирект с корня на логин
  { path: '/', redirect: '/login' },

  // публичные страницы
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },

  // защищённые страницы
  { path: '/dashboard', name: 'Dashboard', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/users', name: 'Users', component: Users, meta: { requiresAuth: true } },
  { path: '/reports', name: 'Reports', component: Reports, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore()

  // проверка токена, если есть
  if (!userStore.isAuthChecked && userStore.token) {
    try {
      await userStore.checkToken()
    } catch {
      userStore.logout()
    }
  }

  // если нужна авторизация, а токена нет → на логин
  if (to.meta.requiresAuth && !userStore.token) {
    return next('/login')
  }

  // если уже авторизован, но пытается зайти на логин/регистрацию → редирект в дашборд
  if ((to.name === 'Login' || to.name === 'Register') && userStore.token) {
    return next('/dashboard')
  }

  next()
})

export default router
