// resources/js/stores/authStore.js
import { defineStore } from 'pinia'
import axios from 'axios'
import router from '../router'
import echo, { subscribeToNotifications } from '../echo'
import { useNotificationsStore } from '@/stores/notificationsStore'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    isAuthenticated: false,
    errors: [],
    loading: false
  }),
  actions: {
    async initialize() {
      const token = localStorage.getItem('sanctum_token')
      if (token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        try {
          const response = await axios.get('/api/user')
          this.user = response.data
          this.isAuthenticated = true
          // Subscribe kênh realtime cho user khi đã lấy được thông tin user
          subscribeToNotifications(this.user.id)
        } catch (error) {
          this.user = null
          this.isAuthenticated = false
          localStorage.removeItem('sanctum_token')
        }
      } else {
        this.user = null
        this.isAuthenticated = false
      }
    },
    async checkAuth() {
      try {
        const response = await axios.get('/api/user')
        this.user = response.data
        this.isAuthenticated = true
      } catch (error) {
        this.user = null
        this.isAuthenticated = false
      }
    },
    async register(formData) {
      this.loading = true
      this.errors = []
      try {
        await axios.get('/sanctum/csrf-cookie')
        await axios.post('/api/register', formData)
        await this.login({
          email: formData.email,
          password: formData.password
        })
        router.push('/dashboard')
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = Object.values(error.response.data.errors).flat()
        } else {
          this.errors = [error.response?.data?.message || 'Đăng ký thất bại']
        }
      } finally {
        this.loading = false
      }
    },
    async login(formData) {
      this.loading = true
      this.errors = []
      try {
        const response = await axios.post('/api/login', formData)
        const token = response.data.token
        if (token) {
          localStorage.setItem('sanctum_token', token)
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
          await this.initialize() // Lấy thông tin user và subscribe kênh realtime
          // Reload trang sau khi chuyển hướng để cập nhật lại subscription mới
          router.push('/dashboard').then(() => {
            window.location.reload()
          })
        } else {
          this.errors = ['Token không tồn tại trong phản hồi đăng nhập']
        }
      } catch (error) {
        this.errors = [error.response?.data?.message || 'Login failed. Please try again.']
      } finally {
        this.loading = false
      }
    },
    async updateProfile(profileData) {
      try {
        const response = await axios.put('/api/user/profile', profileData)
        this.user = response.data
        return response.data
      } catch (error) {
        throw new Error(error.response?.data?.message || 'Lỗi cập nhật thông tin')
      }
    },
    async updatePassword(passwordData) {
      this.loading = true;
      try {
        const { data } = await axios.put('/api/user/password', passwordData);
        return data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Lỗi cập nhật mật khẩu';
        throw new Error(this.error);
      } finally {
        this.loading = false;
      }
    },
    async logout() {
      this.loading = true
      this.errors = []
      try {
        await axios.post('/api/logout')
        localStorage.removeItem('sanctum_token')
        // Ngắt kết nối Echo để hủy đăng ký kênh realtime cũ
        echo.disconnect()
        // Xoá hết các thông báo của tài khoản cũ
        const notificationsStore = useNotificationsStore()
        notificationsStore.clearNotifications()
        this.user = null
        this.isAuthenticated = false
        router.push('/login')
      } catch (error) {
        this.errors = ['Logout failed. Please try again.']
      } finally {
        this.loading = false
      }
    }
  }
})
