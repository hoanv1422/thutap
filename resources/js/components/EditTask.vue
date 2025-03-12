<template>
  <div class="container mt-5">
    <h1 class="mb-4">Chỉnh sửa Công việc</h1>
    <form @submit.prevent="handleSubmit" novalidate>
      <!-- Tên công việc -->
      <div class="mb-3">
        <label for="name" class="form-label">Tên Công việc</label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          class="form-control"
          placeholder="Nhập tên công việc"
          required
        />
        <div v-if="errors.name" class="text-danger mt-1">{{ errors.name }}</div>
      </div>
      
      <!-- Mô tả -->
      <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea
          id="description"
          v-model="form.description"
          class="form-control"
          placeholder="Nhập mô tả"
          required
        ></textarea>
        <div v-if="errors.description" class="text-danger mt-1">{{ errors.description }}</div>
      </div>
      
      <!-- Ngày hết hạn -->
      <div class="mb-3">
        <label for="deadline" class="form-label">Ngày hết hạn</label>
        <input
          id="deadline"
          v-model="form.deadline"
          type="date"
          class="form-control"
        />
        <div v-if="errors.deadline" class="text-danger mt-1">{{ errors.deadline }}</div>
      </div>
      
      <!-- Trạng thái -->
      <div class="mb-3">
        <label for="status" class="form-label">Trạng thái</label>
        <select id="status" v-model="form.status" class="form-select">
          <option value="pending">Chưa bắt đầu</option>
          <option value="in_progress">Đang thực hiện</option>
          <option value="completed">Đã hoàn thành</option>
          <option value="canceled">Đã hủy</option>
        </select>
      </div>
      
      <!-- Multi-select: Người được phân công -->
      <div class="mb-3">
        <label for="assigned_user_ids" class="form-label">Người được phân công</label>
        <select
          id="assigned_user_ids"
          v-model="form.assigned_user_ids"
          class="form-select"
          multiple
        >
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.name }}
          </option>
        </select>
        <div v-if="errors.assigned_user_ids" class="text-danger mt-1">
          {{ errors.assigned_user_ids }}
        </div>
      </div>
      
      <!-- Nút submit và hủy -->
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
        <router-link :to="`/tasks/${taskId}`" class="btn btn-secondary">Hủy</router-link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const taskId = route.params.id

// Form dữ liệu chỉnh sửa công việc, sử dụng assigned_user_ids là mảng
const form = ref({
  name: '',
  description: '',
  deadline: '',
  status: 'pending',
  assigned_user_ids: [],
})

const users = ref([])
const errors = ref({})

// Hàm lấy danh sách người dùng
const fetchUsers = async () => {
  try {
    const response = await axios.get('/api/users')
    users.value = response.data.data || response.data
  } catch (error) {
    console.error('Lỗi tải danh sách người dùng:', error)
  }
}

// Hàm lấy dữ liệu công việc cần chỉnh sửa
const fetchTask = async () => {
  try {
    const response = await axios.get(`/api/tasks/${taskId}`)
    const data = response.data.data
    form.value = {
      name: data.name,
      description: data.description,
      deadline: data.deadline,
      status: data.status,
      // Giả sử API trả về danh sách người được phân công trong key "users"
      assigned_user_ids: data.users ? data.users.map(u => u.id) : [],
    }
  } catch (error) {
    console.error('Lỗi khi tải dữ liệu công việc:', error)
    alert('Lỗi khi tải dữ liệu công việc')
  }
}

// Hàm submit form cập nhật công việc
const handleSubmit = async () => {
  errors.value = {} // Reset lỗi trước khi gửi
  try {
    await axios.put(`/api/tasks/${taskId}`, form.value)
    alert('Công việc đã được cập nhật thành công!')
    router.push(`/tasks/${taskId}`)
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      errors.value = error.response.data.errors
    }
    alert(error.response?.data.message || 'Lỗi cập nhật công việc')
  }
}

onMounted(() => {
  fetchUsers()
  fetchTask()
})
</script>

<style scoped>
.container {
  max-width: 600px;
  margin: auto;
}
</style>
