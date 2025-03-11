<template>
    <div class="profile">
      <h1>Thông tin cá nhân</h1>
      <div v-if="loading">Đang tải...</div>
      <div v-else-if="error" class="error">{{ error }}</div>
      <div v-else>
        <div class="avatar">
          <img
            :src="user.avatar || 'https://via.placeholder.com/150'"
            alt="Avatar"
            class="rounded-circle"
            style="width: 150px; height: 150px; object-fit: cover;"
          />
        </div>
        <div class="user-info">
          <p><strong>Tên:</strong> {{ user.name }}</p>
          <p><strong>Email:</strong> {{ user.email }}</p>
        </div>
        <router-link to="/profile/edit" class="btn btn-primary">Chỉnh sửa thông tin</router-link>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useAuthStore } from '../stores/authStore';
  
  const authStore = useAuthStore();
  const user = ref(null);
  const loading = ref(true);
  const error = ref(null);
  
  onMounted(async () => {
    try {
      await authStore.checkAuth(); 
      user.value = authStore.user; 
    } catch (err) {
      error.value = 'Lỗi tải thông tin người dùng';
    } finally {
      loading.value = false;
    }
  });
  </script>
  
  <style scoped>
  .profile {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
  }
  
  .avatar {
    margin-bottom: 20px;
  }
  
  .user-info {
    margin-bottom: 20px;
  }
  
  .user-info p {
    font-size: 1.1rem;
    color: #555;
  }
  
  .error {
    color: red;
    font-weight: bold;
  }
  </style>