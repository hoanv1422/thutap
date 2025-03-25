<template>
  <div class="container mt-5">
    <div class="card shadow-sm profile-card" v-if="task">
      <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Chi tiết Công việc</h2>
      </div>
      <div class="card-body">
        <h4 class="card-title">{{ task.name }}</h4>
        <p class="card-text"><strong>Mô tả:</strong> {{ task.description || 'Không có mô tả' }}</p>

        <div class="row mb-3">
          <div class="col-md-6">
            <p>
              <strong>Trạng thái:</strong>
              <span class="badge" :class="getStatusClass(task.status)">
                {{ getStatusLabel(task.status) }}
              </span>
            </p>
          </div>
          <div class="col-md-6">
            <p>
              <strong>Ưu tiên:</strong>
              <span class="badge bg-info">
                {{ task.priority }}
              </span>
            </p>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <p><strong>Thời gian bắt đầu:</strong> {{ formatDate(task.start_time) }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Ngày hết hạn:</strong> {{ formatDate(task.deadline) }}</p>
          </div>
        </div>

        <p>
          <strong>Người được phân công:</strong>
          <span v-if="task.users && task.users.length">
            {{task.users.map(u => u.name).join(', ')}}
          </span>
          <span v-else>
            Chưa phân công
          </span>
        </p>
      </div>
      <div class="card-footer d-flex justify-content-end">
        <router-link :to="`/tasks/${task.id}/edit`" class="btn btn-warning me-2">Chỉnh sửa</router-link>
        <router-link to="/tasks" class="btn btn-secondary">Quay lại danh sách</router-link>
      </div>
    </div>
    <div v-else class="text-center">
      <p>Đang tải dữ liệu...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const taskId = route.params.id
const task = ref(null)

const fetchTask = async () => {
  try {
    const response = await axios.get(`/api/tasks/${taskId}`)
    // Giả sử dữ liệu task nằm trong response.data.data
    task.value = response.data.data
  } catch (error) {
    alert('Lỗi tải dữ liệu công việc')
  }
}

const getStatusLabel = (status) => {
  switch (status) {
    case 'pending':
      return 'Đang chờ xử lý'
    case 'in_progress':
      return 'Đang thực hiện'
    case 'completed':
      return 'Đã hoàn thành'
    case 'canceled':
      return 'Đã hủy'
    default:
      return 'Không xác định'
  }
}

const getStatusClass = (status) => {
  switch (status) {
    case 'pending':
      return 'bg-secondary'
    case 'in_progress':
      return 'bg-info'
    case 'completed':
      return 'bg-success'
    case 'canceled':
      return 'bg-danger'
    default:
      return 'bg-light text-dark'
  }
}

const formatDate = (date) => {
  if (!date) return 'Không có'
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(date).toLocaleDateString('vi-VN', options)
}

onMounted(() => {
  fetchTask()
})
</script>

<style scoped>
.container {
  max-width: 700px;
  margin: auto;
}

.profile-card {
  border: none;
  border-radius: 10px;
  overflow: hidden;
}

.card-header {
  padding: 1rem 1.5rem;
  border-bottom: 2px solid rgba(255, 255, 255, 0.3);
}

.card-body {
  padding: 1.5rem;
}

.card-title {
  font-size: 1.75rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.card-text {
  font-size: 1rem;
  color: #555;
}

.row p {
  margin-bottom: 0.5rem;
}

.badge {
  font-size: 0.9rem;
  padding: 0.5em 0.75em;
}

.card-footer {
  background-color: #f8f9fa;
  padding: 0.75rem 1.5rem;
}

.btn {
  min-width: 130px;
}
</style>
