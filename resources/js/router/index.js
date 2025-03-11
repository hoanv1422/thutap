import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/authStore' 
import Dashboard from '../views/Dashboard.vue'
import Login from '../views/Login.vue' 
import Register from '../views/Register.vue' 
import Posts from '../views/Posts.vue'
import CreatePost from '../components/CreatePost.vue'; 
const routes = [
  {
    path: '/',
    redirect: '/dashboard'
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: false }
  },
  {
    path: '/login',
    name: 'login',
    component: Login, 
    meta: { guestOnly: true } 
  },
  {
    path: '/register',
    name: 'register',
    component: Register, 
    meta: { guestOnly: true }
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('../views/Profile.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/profile/edit',
    name: 'edit-profile',
    component: () => import('../components/EditProfile.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/posts',
    name: 'posts',
    component: Posts,
    meta: { requiresAuth: true }
  },
  {
    path: '/posts/create',
    name: 'create-post',
    component: CreatePost,
    meta: { requiresAuth: true }
  },
  {
    path: '/posts/:id', 
    name: 'post-detail',
    component: () => import('../components/PostDetail.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/posts/:id/edit', 
    name: 'edit-post',
    component: () => import('../components/EditPost.vue'),
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})


router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  // Kiểm tra trạng thái xác thực
  await authStore.checkAuth()
  
  // Xử lý route yêu cầu đăng nhập
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } 
  // Xử lý route chỉ dành cho khách
  else if (to.meta.guestOnly && authStore.isAuthenticated) {
    next('/dashboard')
  } 
  // Cho phép truy cập
  else {
    next()
  }
})

export default router