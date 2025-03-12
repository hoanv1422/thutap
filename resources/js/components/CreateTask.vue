<template>
  <div class="container mt-5">
    <h1 class="mb-4 text-center">Thêm mới Công việc</h1>
    <form @submit.prevent="handleSubmit" novalidate>
      <div class="row">
        <!-- Cột trái -->
        <div class="col-md-6">
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
          <!-- Thời gian bắt đầu -->
          <div class="mb-3">
            <label for="start_time" class="form-label">Thời gian bắt đầu</label>
            <input
              id="start_time"
              v-model="form.start_time"
              type="datetime-local"
              class="form-control"
            />
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
        </div>

        <!-- Cột phải -->
        <div class="col-md-6">
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
          <!-- Ưu tiên -->
          <div class="mb-3">
            <label for="priority" class="form-label">Ưu tiên</label>
            <select id="priority" v-model="form.priority" class="form-select">
              <option value="low">Thấp</option>
              <option value="medium">Trung bình</option>
              <option value="high">Cao</option>
              <option value="urgent">Khẩn cấp</option>
            </select>
          </div>
          <!-- Tiến độ -->
          <div class="mb-3">
            <label for="progress" class="form-label">Tiến độ (%)</label>
            <input
              id="progress"
              v-model.number="form.progress"
              type="number"
              min="0"
              max="100"
              class="form-control"
            />
          </div>
          <!-- Danh sách checkbox cho Người được phân công -->
          <div class="mb-3">
            <label class="form-label">Người được phân công</label>
            <div v-if="users.length">
              <div v-for="user in users" :key="user.id" class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  :id="'user-' + user.id"
                  :value="user.id"
                  v-model="form.assigned_user_ids"
                />
                <label class="form-check-label" :for="'user-' + user.id">
                  {{ user.name }}
                </label>
              </div>
            </div>
            <div v-else class="text-muted">
              Đang tải danh sách người dùng...
            </div>
            <div v-if="errors.assigned_user_ids" class="text-danger mt-1">
              {{ errors.assigned_user_ids }}
            </div>
          </div>
          <!-- Ghi chú -->
          <div class="mb-3">
            <label for="notes" class="form-label">Ghi chú</label>
            <textarea
              id="notes"
              v-model="form.notes"
              class="form-control"
              placeholder="Nhập ghi chú"
            ></textarea>
          </div>
        </div>
      </div>
      <!-- Nút submit và hủy -->
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary me-2">Tạo Công việc</button>
        <router-link to="/tasks" class="btn btn-secondary">Hủy</router-link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

// Form dữ liệu sử dụng assigned_user_ids dưới dạng mảng
const form = ref({
  name: '',
  description: '',
  start_time: '',
  deadline: '',
  status: 'pending',
  priority: 'medium',
  progress: 0,
  assigned_user_ids: [],
  notes: '',
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

const handleSubmit = async () => {
  errors.value = {} // Reset lỗi
  try {
    const response = await axios.post('/api/tasks', form.value)
    alert('Công việc đã được tạo thành công!')
    router.push(`/tasks/${response.data.data.id}`)
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      errors.value = error.response.data.errors
    }
    alert(error.response?.data.message || 'Lỗi tạo công việc')
  }
}

fetchUsers()
</script>

<style scoped>
.container {
  max-width: 600px;
  margin: auto;
}
</style>
