<template>
    <div class="auth-form">
      <h2>Edit User</h2>
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
          <label>Password (Leave blank to keep current)</label>
          <input 
            v-model="form.password" 
            type="password" 
            class="form-control"
          >
        </div>
  
        <button type="submit" class="btn btn-primary w-100">
          Update User
        </button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    props: {
      userId: {
        type: [String, Number],
        required: true
      }
    },
    data() {
      return {
        form: {
          id: null,
          name: '',
          email: '',
          password: ''
        }
      };
    },
    async mounted() {
      console.log("User ID:", this.userId);
      this.loadUserData();
    },
    methods: {
      async loadUserData() {
        try {
          const response = await axios.get(
            `http://localhost:8000/api/users/${this.userId}`
          );
          this.form = {
            ...response.data,
            password: ''
          };
        } catch (error) {
          this.handleError(error, 'Failed to load user data');
        }
      },
      async handleSubmit() {
        try {
          const payload = {
            name: this.form.name,
            email: this.form.email,
            ...(this.form.password && { password: this.form.password })
          };
  
          await axios.put(
            `http://localhost:8000/api/users/${this.userId}`,
            payload
          );
  
          this.$emit('updated'); // Emit event khi cập nhật thành công
          this.$router.push('/dashboard');
        } catch (error) {
          this.handleError(error, 'Update failed');
        }
      },
      handleError(error) {
        const message = error.response?.data?.message || 'An error occurred';
        alert(message);
      }
    }
  };
  </script>