<template>
    <div class="auth-form">
      <h2>{{ editMode ? 'Edit User' : 'Create User' }}</h2>
      <form @submit.prevent="handleSubmit">
        <div class="mb-3">
          <label>Name</label>
          <input v-model="form.name" type="text" class="form-control" required>
        </div>
  
        <div class="mb-3">
          <label>Email</label>
          <input v-model="form.email" type="email" class="form-control" required>
        </div>
  
        <div class="mb-3">
          <label>Password</label>
          <input 
            v-model="form.password" 
            type="password" 
            class="form-control"
            :required="!editMode">
        </div>
  
        <button type="submit" class="btn btn-primary w-100">
          {{ editMode ? 'Update' : 'Create' }}
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
      default: null
    }
  },
  data() {
    return {
      form: {
        id: null, 
        name: '',
        email: '',
        password: ''
      },
      editMode: false
    };
  },
  async created() {
    if (this.userId) {
      this.editMode = true;
      try {
        const response = await axios.get(
          `http://localhost:8000/api/users/${this.userId}`
        );
   
        this.form = { 
          ...response.data,
          password: '' 
        };
      } catch (error) {
        alert('Failed to load user data');
        this.$router.back();
      }
    }
  },
  methods: {
    async handleSubmit() {
      try {
        const payload = { ...this.form };
        
        if (this.editMode) {
          const url = `http://localhost:8000/api/users/${this.form.id}`;
          await axios.put(url, payload);
        } else {
          await axios.post('http://localhost:8000/api/users', payload);
        }

        this.$emit('refresh');
        this.$router.push('/dashboard');
      } catch (error) {
        const msg = this.editMode 
          ? 'Update failed' 
          : 'Creation failed';
        alert(`${msg}: ${error.response?.data?.message || error.message}`);
      }
    }
  }
};
</script>