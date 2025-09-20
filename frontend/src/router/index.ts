import { createRouter, createWebHistory } from 'vue-router';
import { useUserStore } from '@/stores/userStore';
import Login from '@/views/Login.vue';
import Register from '@/views/Register.vue';
import Dashboard from '@/views/Dashboard.vue';
import Users from '@/views/Users.vue';
import Reports from '@/views/Reports.vue';
import Search from '@/views/Search.vue';

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/users', component: Users, meta: { requiresAuth: true } },
  { path: '/reports', component: Reports, meta: { requiresAuth: true } },
  { path: '/search', component: Search },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore();

  if (!userStore.isAuthChecked && userStore.token) {
    try {
      await userStore.checkToken();
    } catch {
      userStore.logout();
    }
  }

  if (to.meta.requiresAuth && !userStore.isAuthenticated) {
    return next('/login');
  }

  if ((to.path === '/login' || to.path === '/register') && userStore.isAuthenticated) {
    return next('/dashboard');
  }

  next();
});

export default router;
