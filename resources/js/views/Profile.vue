<template>
  <div class="profile container mt-5">
    <div class="card profile-card" v-if="user">
      <div class="card-body text-center">
        <img
          :src="user?.avatar || 'https://via.placeholder.com/150'"
          alt="Avatar"
          class="avatar rounded-circle mb-3"
        />
        <h2 class="user-name mb-2">{{ user?.name }}</h2>
        <p class="user-email mb-1"><strong>Email:</strong> {{ user?.email }}</p>
        <p class="user-phone mb-1" v-if="user?.phone">
          <strong>Số điện thoại:</strong> {{ user.phone }}
        </p>
        <p class="user-role mb-1" v-if="user?.role">
          <strong>Vai trò:</strong> {{ user.role }}
        </p>
        <p class="user-created mb-3" v-if="user?.created_at">
          <strong>Ngày tham gia:</strong> {{ formatDate(user.created_at) }}
        </p>
        <div class="btn-group">
          <router-link to="/profile/edit" class="btn btn-primary me-2">Chỉnh sửa thông tin</router-link>
          <router-link to="/profile/password" class="btn btn-warning">Thay đổi mật khẩu</router-link>
        </div>
      </div>
    </div>
    <div v-else-if="loading" class="alert alert-info text-center">Đang tải...</div>
    <div v-else-if="error" class="alert alert-danger text-center">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../stores/authStore'

const authStore = useAuthStore()
const user = ref(null)
const loading = ref(true)
const error = ref(null)

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('vi-VN', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

onMounted(async () => {
  try {
    await authStore.checkAuth()
    user.value = authStore.user
  } catch (err) {
    error.value = 'Lỗi tải thông tin người dùng'
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.profile {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.profile-card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.avatar {
  width: 150px;
  height: 150px;
  object-fit: cover;
  border: 3px solid #007bff;
}

.user-name {
  font-size: 2rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.user-email,
.user-phone,
.user-role,
.user-created {
  font-size: 1.1rem;
  color: #555;
}

.btn-group {
  margin-top: 15px;
}

.btn-group .btn {
  min-width: 150px;
  margin: 0 5px;
}
</style>
