import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/authStore'
import Dashboard from '../views/Dashboard.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Posts from '../views/Posts.vue'
import CreatePost from '../components/CreatePost.vue'
import Tasks from '../components/Task.vue'
import UserList from '@/views/UserList.vue'
import UserProfile from '@/views/UserProfile.vue'

// Sử dụng lazy load cho các component cần thiết
const EditProfile = () => import('../components/EditProfile.vue')
const ChangePassword = () => import('../components/ChangePassword.vue')
const EditUser = () => import('@/views/UserEdit.vue')

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
    component: EditProfile,
    meta: { requiresAuth: true }
  },
  {
    path: '/profile/password',
    name: 'change-password',
    component: ChangePassword,
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
  },
  {
    path: '/Tasks',
    name: 'Task',
    component: Tasks,
    meta: { requiresAuth: true }
  },
  {
    path: '/Tasks/create',
    name: 'create-Task',
    component: () => import('../components/CreateTask.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/Tasks/:id',
    name: 'Task-detail',
    component: () => import('../components/TaskDetail.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/Tasks/:id/edit',
    name: 'edit-Task',
    component: () => import('../components/EditTask.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/users',
    name: 'user-list',
    component: UserList,
    meta: { requiresAuth: true }
  },
  {
    path: '/users/:id',
    name: 'user-profile',
    component: UserProfile,
    meta: { requiresAuth: true }
  },
  {
    path: '/users/:id/edit',
    name: 'edit-user',
    component: EditUser,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  await authStore.checkAuth()

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else if (to.meta.guestOnly && authStore.isAuthenticated) {
    next('/dashboard')
  } else if (to.name === 'edit-user' && authStore.user.role !== 'admin' && to.params.id !== authStore.user.id) {
    alert('Bạn không có quyền chỉnh sửa người dùng khác')
    next('/users')
  } else {
    next()
  }
})

export default router
