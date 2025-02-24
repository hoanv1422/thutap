<template>
    <div class="user-management">
      <!-- Header -->
      <div class="management-header">
        <h2 class="management-title">User Management</h2>
        <router-link 
          to="/users/create" 
          class="btn btn-primary btn-add"
        >
          <i class="fas fa-plus-circle me-2"></i>
          Add New User
        </router-link>
      </div>
  
      <!-- Loading state -->
      <div v-if="loading" class="loading-overlay">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
  
      <!-- Error message -->
      <div v-else-if="error" class="alert alert-danger">
        {{ error }}
        <button 
          @click="refreshUsers" 
          class="btn btn-link btn-retry"
        >
          Retry
        </button>
      </div>
  
      <!-- User table -->
      <template v-else>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id">
                <td class="user-name">{{ user.name }}</td>
                <td class="user-email">{{ user.email }}</td>
                <td class="user-actions">
                  <router-link
                    :to="`/users/edit/${user.id}`"
                    class="btn btn-warning btn-sm btn-edit"
                  >
                    <i class="fas fa-edit me-1"></i>
                    Edit
                  </router-link>
                  <button
                    @click="handleDelete(user.id)"
                    class="btn btn-danger btn-sm btn-delete"
                  >
                    <i class="fas fa-trash-alt me-1"></i>
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
  
          <!-- Empty state -->
          <div v-if="users.length === 0" class="empty-state">
            <i class="fas fa-users-slash empty-icon"></i>
            <p class="empty-text">No users found</p>
          </div>
        </div>
      </template>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        users: [],
        loading: true,
        error: null
      };
    },
    async mounted() {
      await this.fetchUsers();
    },
    methods: {
      async fetchUsers() {
        try {
          const response = await axios.get('http://localhost:8000/api/users');
          this.users = response.data;
          this.error = null;
        } catch (error) {
          this.handleError(error, 'Failed to load users');
        } finally {
          this.loading = false;
        }
      },
  
      async handleDelete(id) {
        if (!confirm('Are you sure you want to delete this user?')) return;
        
        try {
          await axios.delete(`http://localhost:8000/api/users/${id}`);
          this.users = this.users.filter(user => user.id !== id);
          this.showSuccess('User deleted successfully');
        } catch (error) {
          this.handleError(error, 'Delete failed');
        }
      },
  
      async refreshUsers() {
        this.loading = true;
        await this.fetchUsers();
      },
  
      handleError(error, defaultMessage = 'An error occurred') {
        this.error = error.response?.data?.message || defaultMessage;
        console.error(error);
      },
  
      showSuccess(message) {
        alert(message); 
      }
    }
  };
  </script>
  
  <style scoped>
  .user-management {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
  }
  
  .management-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
  }
  
  .management-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
  }
  
  .btn-add {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
  }
  
  .loading-overlay {
    display: flex;
    justify-content: center;
    padding: 3rem 0;
  }
  
  .empty-state {
    text-align: center;
    padding: 4rem 0;
    color: #6c757d;
  }
  
  .empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
  }
  
  .empty-text {
    font-size: 1.25rem;
  }
  
  .table-responsive {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }
  
  .user-actions {
    min-width: 200px;
  }
  
  .btn-edit {
    margin-right: 0.5rem;
  }
  
  .btn-retry {
    padding: 0;
    vertical-align: baseline;
  }
  
  .alert {
    margin: 2rem 0;
  }
  
  .user-name {
    font-weight: 500;
  }
  
  .user-email {
    color: #6c757d;
  }
  </style>