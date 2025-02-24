<template>
    <div class="auth-form">
      <h2 class="mb-4">Register</h2>
      <form @submit.prevent="handleRegister">
        <div class="mb-3">
          <label>Name</label>
          <input 
            v-model="form.name" 
            type="text" 
            class="form-control" 
            required
          >
        </div>
  
        <div class="mb-3">
          <label>Email</label>
          <input 
            v-model="form.email" 
            type="email" 
            class="form-control" 
            required
          >
        </div>
  
        <div class="mb-3">
          <label>Password</label>
          <input 
            v-model="form.password" 
            type="password" 
            class="form-control" 
            required
            minlength="6"
          >
        </div>
  
        <button type="submit" class="btn btn-primary w-100">Register</button>
        
        <div class="mt-3 text-center">
          Already have an account? 
          <router-link to="/login">Login here</router-link>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  import { useAuthStore } from '../../stores/auth';
  
  export default {
    data() {
      return {
        form: {
          name: '',
          email: '',
          password: ''
        }
      };
    },
    methods: {
      async handleRegister() {
        try {
          await useAuthStore().register(this.form);
          alert('Registration successful! Please login.');
          this.$router.push('/login');
        } catch (error) {
          alert('Registration failed!');
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .auth-form {
    max-width: 400px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
  </style>