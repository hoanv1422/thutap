<template>
  <div class="container mt-5">
    <div class="card profile-card mx-auto">
      <div class="card-body">
        <h1 class="card-title text-center mb-4">Thông Tin Người Dùng</h1>
        
        <div v-if="userStore.loading" class="alert alert-info text-center">
          Đang tải...
        </div>
        <div v-else-if="userStore.error" class="alert alert-danger text-center">
          {{ userStore.error }}
        </div>
        <div v-else-if="user" class="text-center">
          <img v-if="user.avatar" :src="user.avatar" alt="Avatar" class="avatar mb-3" />
          <h2 class="user-name">{{ user.name }}</h2>
          <p><strong>Email:</strong> {{ user.email }}</p>
          <p><strong>Điện thoại:</strong> {{ user.phone || 'Chưa cập nhật' }}</p>
          <p><strong>Vai trò:</strong> {{ user.role }}</p>
          <p><strong>Ngày tạo:</strong> {{ formatDate(user.created_at) }}</p>
          <div class="mt-4">
            <router-link :to="`/users/${user.id}/edit`" class="btn btn-primary me-2">Chỉnh sửa</router-link>
            <router-link to="/users" class="btn btn-secondary">Quay lại</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
import { onMounted, computed } from 'vue'
import { useUserStore } from '../stores/userStore'

const userStore = useUserStore()
const route = useRoute()
const userId = route.params.id

onMounted(() => {
  userStore.fetchUserById(userId)
})

const user = computed(() => userStore.selectedUser)

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('vi-VN')
}
</script>

<style scoped>
.container {
  max-width: 700px;
}

.profile-card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.card-body {
  padding: 2rem;
}

.avatar {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #007bff;
}

.user-name {
  font-size: 1.75rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.btn {
  font-size: 1rem;
  padding: 0.5rem 1rem;
}

.alert {
  font-size: 1.1rem;
}
</style>
