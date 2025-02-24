<template>
    <div class="auth-form">
      <h2 class="mb-4">Login</h2>
      <form @submit.prevent="handleLogin">
        <div class="mb-3">
          <label>Email</label>
          <input v-model="form.email" type="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input v-model="form.password" type="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </template>
  
  <script>
  import { useAuthStore } from '../../stores/auth';
  
  export default {
    data() {
      return {
        form: {
          email: '',
          password: '',
        },
      };
    },
    methods: {
      async handleLogin() {
        try {
          await useAuthStore().login(this.form);
          this.$router.push('/dashboard');
        } catch (error) {
          alert('Login failed!');
        }
      },
    },
  };
  </script>