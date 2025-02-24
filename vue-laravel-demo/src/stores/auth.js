import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
  }),

  actions: {
    async login(credentials) {
      const response = await axios.post('http://localhost:8000/api/login', credentials);
      this.token = response.data.token;
      this.user = response.data.user;
      localStorage.setItem('token', this.token);
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
    },

    async register(userData) {
      await axios.post('http://localhost:8000/api/register', userData);
    },

    logout() {
      axios.post('http://localhost:8000/api/logout');
      this.user = null;
      this.token = null;
      localStorage.removeItem('token');
      delete axios.defaults.headers.common['Authorization'];
    },
  },
});