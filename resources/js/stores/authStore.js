import { defineStore } from 'pinia'
import axios from 'axios'
import router from '../router'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    isAuthenticated: false,
    errors: []
  }),
  actions: {
    async initialize() {
        try {
          const response = await axios.get('/api/user');
          this.user = response.data;
          this.isAuthenticated = true;
        } catch (error) {
          this.user = null;
          this.isAuthenticated = false;
        }
      },
      async register(formData) {
        this.loading = true 
        this.errors = []
        
        try {
          await axios.get('/sanctum/csrf-cookie')
          const response = await axios.post('/api/register', formData)

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
        try {
            const response = await axios.post('/api/login', formData);
            const token = response.data.token;
            localStorage.setItem('auth_token', token);
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            
            router.push('/dashboard');
        } catch (error) {
            this.errors = [error.response?.data?.message || 'Login failed. Please try again.'];
        }
    },
    async checkAuth() {
        try {
          const response = await axios.get('/api/user');
          this.user = response.data;
          this.isAuthenticated = true;
        } catch (error) {
          this.user = null;
          this.isAuthenticated = false;
        }
      },
      async updateProfile(profileData) {
        try {
          const response = await axios.put('/api/user/profile', profileData);
          this.user = response.data; 
          return response.data;
        } catch (error) {
          throw new Error(error.response?.data.message || 'Lỗi cập nhật thông tin');
        }
      },

    async logout() {
      try {
      
        await axios.post('/api/logout')
        this.user = null
        this.isAuthenticated = false
        router.push('/login')
      } catch (error) {
        this.errors = ['Logout failed. Please try again.']
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
    }
  }
})