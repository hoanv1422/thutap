import { defineStore } from 'pinia'
import axios from 'axios'
import router from '../router'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    isAuthenticated: false,
    errors: [],
    loading: false
  }),
  actions: {
    // Hàm khởi tạo: kiểm tra token, cấu hình header và lấy thông tin user nếu có token
    async initialize() {
      const token = localStorage.getItem('auth_token')
      if (token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        try {
          const response = await axios.get('/api/user')
          this.user = response.data
          this.isAuthenticated = true
        } catch (error) {
          this.user = null
          this.isAuthenticated = false
          localStorage.removeItem('auth_token')
        }
      } else {
        this.user = null
        this.isAuthenticated = false
      }
    },
    // Hàm kiểm tra xác thực: gọi API để xác minh token
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
    // Đăng ký người dùng mới và tự động đăng nhập sau đăng ký thành công
    async register(formData) {
      this.loading = true
      this.errors = []
      try {
        // Lấy cookie CSRF nếu dùng Sanctum
        await axios.get('/sanctum/csrf-cookie')
        await axios.post('/api/register', formData)
        // Sau khi đăng ký, tự động đăng nhập
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
    // Đăng nhập người dùng
    async login(formData) {
      this.loading = true
      this.errors = []
      try {
        const response = await axios.post('/api/login', formData)
        const token = response.data.token
        if (token) {
          localStorage.setItem('auth_token', token)
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
          await this.initialize()
          router.push('/dashboard')
        } else {
          this.errors = ['Token không tồn tại trong phản hồi đăng nhập']
        }
      } catch (error) {
        this.errors = [error.response?.data?.message || 'Login failed. Please try again.']
      } finally {
        this.loading = false
      }
    },
    // Cập nhật thông tin người dùng
    async updateProfile(profileData) {
      try {
        const response = await axios.put('/api/user/profile', profileData)
        this.user = response.data
        return response.data
      } catch (error) {
        throw new Error(error.response?.data?.message || 'Lỗi cập nhật thông tin')
      }
    },
    // Đăng xuất người dùng
    async logout() {
      this.loading = true
      this.errors = []
      try {
        await axios.post('/api/logout')
        localStorage.removeItem('auth_token')
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
