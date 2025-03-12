<template>
  <div class="container mt-5">
    <h1 class="mb-4">Chi tiết Công việc</h1>
    <div v-if="task">
      <p><strong>Tên công việc:</strong> {{ task.name }}</p>
      <p><strong>Mô tả:</strong> {{ task.description }}</p>
      <p><strong>Trạng thái:</strong> {{ getStatusLabel(task.status) }}</p>
      <p><strong>Thời gian bắt đầu:</strong> {{ formatDate(task.start_time) }}</p>
      <p><strong>Ngày hết hạn:</strong> {{ formatDate(task.deadline) }}</p>
      <p>
        <strong>Người được phân công:</strong>
        <!-- Hiển thị danh sách tên người được phân công -->
        {{ task.users && task.users.length ? task.users.map(u => u.name).join(', ') : 'Chưa phân công' }}
      </p>
      <router-link :to="`/tasks/${task.id}/edit`" class="btn btn-warning me-2">
        Chỉnh sửa
      </router-link>
      <router-link to="/tasks" class="btn btn-secondary">
        Quay lại danh sách
      </router-link>
    </div>
    <div v-else>
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

// Hàm lấy chi tiết công việc từ API
const fetchTask = async () => {
  try {
    const response = await axios.get(`/api/tasks/${taskId}`)
    task.value = response.data.data
  } catch (error) {
    alert('Lỗi tải dữ liệu công việc')
  }
}

// Hàm chuyển đổi trạng thái thành nhãn thân thiện
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

// Hàm định dạng ngày tháng
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
  max-width: 600px;
  margin: auto;
}
</style>
