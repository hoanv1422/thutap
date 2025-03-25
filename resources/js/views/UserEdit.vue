<template>
    <div class="container mt-5 custom-container">
      <h1 class="mb-4 text-center">Chỉnh Sửa Thông Tin Người Dùng</h1>
  
      <div v-if="userStore.loading" class="alert alert-info">
        Đang tải...
      </div>
      <div v-else-if="userStore.error" class="alert alert-danger">
        {{ userStore.error }}
      </div>
      <div v-else-if="!userStore.selectedUser">
        <p class="alert alert-warning">Không tìm thấy người dùng!</p>
        <router-link to="/users" class="btn btn-secondary">Quay lại danh sách</router-link>
      </div>
  
      <form v-else @submit.prevent="handleSubmit" class="card p-4 custom-card" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="name" class="form-label">Tên:</label>
          <input type="text" id="name" v-model="form.name" class="form-control custom-input" required />
        </div>
  
        <div class="mb-3">
          <label class="form-label">Email:</label>
          <p class="form-control-plaintext custom-email">{{ form.email }}</p>
        </div>
  
        <div class="mb-3">
          <label for="phone" class="form-label">Số điện thoại:</label>
          <input type="text" id="phone" v-model="form.phone" class="form-control custom-input" />
        </div>
  
        <!-- File input cho avatar (không có trường nhập URL) -->
        <div class="mb-3">
          <label for="avatarFile" class="form-label">Avatar:</label>
          <input type="file" id="avatarFile" @change="handleFileChange" class="form-control custom-input" accept="image/*" />
        </div>
  
        <!-- Chỉ hiển thị trường "role" nếu user hiện tại là admin -->
        <div v-if="authStore.user?.role === 'admin'" class="mb-3">
          <label for="role" class="form-label">Vai trò:</label>
          <select id="role" v-model="form.role" class="form-select custom-select">
            <option value="user">Người dùng</option>
            <option value="admin">Admin</option>
          </select>
        </div>
  
        <button type="submit" class="btn btn-primary me-2 custom-btn">Cập nhật</button>
        <router-link :to="`/users/${userId}`" class="btn btn-secondary mt-2 custom-btn">Hủy</router-link>
      </form>
    </div>
  </template>
  
  <script setup>
  import { useRoute, useRouter } from 'vue-router'
  import { ref, onMounted } from 'vue'
  import { useUserStore } from '../stores/userStore'
  import { useAuthStore } from '../stores/authStore'
  
  const route = useRoute()
  const router = useRouter()
  const userStore = useUserStore()
  const authStore = useAuthStore()
  const userId = route.params.id
  
  // Form chứa dữ liệu người dùng (email chỉ hiển thị)
  const form = ref({
    name: '',
    phone: '',
    role: 'user',
    email: '',
  })
  
  // Biến lưu file avatar được chọn
  const avatarFile = ref(null)
  
  const handleFileChange = (event) => {
    const selectedFile = event.target.files[0]
    if (selectedFile) {
      avatarFile.value = selectedFile
    }
  }
  
  onMounted(async () => {
    await userStore.fetchUserById(userId)
    if (userStore.selectedUser) {
      form.value = { ...userStore.selectedUser }
    }
  })
  
  const handleSubmit = async () => {
    try {
      // Tạo FormData và thêm _method để override thành PUT
      const formData = new FormData()
      formData.append('_method', 'PUT')
      formData.append('name', form.value.name)
      formData.append('phone', form.value.phone)
      formData.append('role', form.value.role)
      // Nếu có file avatar được chọn, thêm vào FormData
      if (avatarFile.value) {
        formData.append('avatar', avatarFile.value)
      }
      await userStore.updateUser(userId, formData)
      await userStore.fetchUserById(userId)
      alert('Cập nhật thành công!')
      router.push(`/users/${userId}`)
    } catch (error) {
      alert(error.message)
    }
  }
  </script>
  
  <style scoped>
  .custom-container {
    max-width: 600px;
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  
  .custom-card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    background-color: #ffffff;
  }
  
  .custom-input {
    border-radius: 4px;
  }
  
  .custom-select {
    border-radius: 4px;
  }
  
  .custom-btn {
    border-radius: 4px;
    min-width: 100px;
  }
  
  .custom-email {
    font-weight: bold;
    color: #555;
  }
  </style>
  