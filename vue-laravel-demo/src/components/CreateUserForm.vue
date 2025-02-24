<template>
    <div class="auth-form">
      <h2>Create New User</h2>
      <form @submit.prevent="handleSubmit">
        <!-- Form fields -->
        <div class="form-group">
          <label>Name</label>
          <input v-model="form.name" type="text" class="form-control" required>
        </div>
  
        <div class="form-group">
          <label>Email</label>
          <input v-model="form.email" type="email" class="form-control" required>
        </div>
  
        <div class="form-group">
          <label>Password</label>
          <input 
            v-model="form.password" 
            type="password" 
            class="form-control"
            required
          >
        </div>
  
        <button type="submit" class="btn btn-primary w-100">
          Create User
        </button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
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
      async handleSubmit() {
        try {
          await axios.post('http://localhost:8000/api/users', this.form);
          this.$emit('created'); // Emit event khi tạo thành công
          this.$router.push('/dashboard');
        } catch (error) {
          this.handleError(error, 'Creation failed');
        }
      },
      handleError(error) {
        const message = error.response?.data?.message || 'An error occurred';
        alert(message);
      }
    }
  };
  </script>