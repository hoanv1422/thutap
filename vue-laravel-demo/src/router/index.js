import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Dashboard from '../views/Dashboard.vue';
import Login from '../components/Auth/Login.vue';
import Register from '../components/Auth/Register.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
  {
    path: '/users/create',
    component: () => import('../components/CreateUserForm.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/users/edit/:id',
    component: () => import('../components/EditUserForm.vue'),
    props: route => ({ userId: route.params.id }),
    meta: { requiresAuth: true }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  if (to.meta.requiresAuth && !token) {
    next('/login');
  } else {
    next();
  }
});


export default router;