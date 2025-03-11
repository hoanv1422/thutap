<template>
    <div class="edit-profile">
      <h1>Chỉnh sửa thông tin cá nhân</h1>
      <div v-if="loading">Đang tải...</div>
      <div v-else-if="error" class="error">{{ error }}</div>
      <form v-else @submit.prevent="handleSubmit">
        <!-- Tên -->
        <div class="form-group">
          <label for="name">Tên</label>
          <input
            type="text"
            id="name"
            v-model="form.name"
            placeholder="Nhập tên"
            required
          />
        </div>
  
        <!-- Email -->
        <div class="form-group">
          <label for="email">Email</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            placeholder="Nhập email"
            required
          />
        </div>
  
        <!-- Avatar -->
        <!-- <div class="form-group">
          <label for="avatar">Avatar (URL)</label>
          <input
            type="text"
            id="avatar"
            v-model="form.avatar"
            placeholder="Nhập URL avatar"
          />
        </div> -->
  
        <!-- Hiển thị lỗi -->
        <div v-if="errors.length" class="error">
          <ul>
            <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
          </ul>
        </div>
  
        <!-- Nút cập nhật và hủy -->
        <div class="actions">
          <button type="submit" class="btn btn-primary">Cập nhật</button>
          <router-link to="/profile" class="btn btn-secondary">Hủy</router-link>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useAuthStore } from '../stores/authStore';
  import { useRouter } from 'vue-router';
  
  const authStore = useAuthStore();
  const router = useRouter();
  const form = ref({
    name: '',
    email: '',
    avatar: ''
  });
  const loading = ref(true);
  const error = ref(null);
  const errors = ref([]);
  
  // Khởi tạo form với dữ liệu hiện tại của user
  onMounted(() => {
    if (authStore.user) {
      form.value.name = authStore.user.name;
      form.value.email = authStore.user.email;
      form.value.avatar = authStore.user.avatar || '';
    }
    loading.value = false;
  });
  
  // Xử lý cập nhật thông tin
  const handleSubmit = async () => {
    try {
      await authStore.updateProfile(form.value);
      alert('Cập nhật thông tin thành công!');
      router.push('/profile'); // Quay lại trang profile sau khi cập nhật
    } catch (err) {
      errors.value = [err.message || 'Lỗi cập nhật thông tin'];
    }
  };
  </script>
  
  <style scoped>
  .edit-profile {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
  }
  
  .form-group {
    margin-bottom: 20px;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }
  
  .form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  .actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
  }
  
  .error {
    color: red;
    margin-bottom: 20px;
  }
  </style>