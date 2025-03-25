<template>
  <div class="container mt-5">
    <div class="card shadow-sm">
      <div class="card-header bg-gradient text-black">
        <h1 class="mb-0">Danh sách Công việc</h1>
      </div>
      <div class="card-body">
        <!-- Thanh lọc công việc theo trạng thái -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <label for="filterStatus" class="form-label me-2">Lọc công việc theo trạng thái:</label>
            <select id="filterStatus" v-model="filterStatus" class="form-select form-select-sm d-inline-block w-auto">
              <option value="">Tất cả</option>
              <option value="pending">Chưa bắt đầu</option>
              <option value="in_progress">Đang thực hiện</option>
              <option value="completed">Đã hoàn thành</option>
            </select>
          </div>
          <router-link to="/tasks/create" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Tạo Công việc Mới
          </router-link>
        </div>

        <div v-if="filteredTasks.length === 0" class="alert alert-info text-center">
          Không có công việc nào phù hợp.
        </div>

        <div v-else class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>Tên công việc</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Ngày hết hạn</th>
                <th>Người được phân công</th>
                <th class="text-center">Hành động</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="task in filteredTasks" :key="task.id">
                <td>{{ task.name }}</td>
                <td>{{ task.description || 'Không có mô tả' }}</td>
                <td>
                  <span :class="getStatusClass(task.status)">
                    {{ translateStatus(task.status) }}
                  </span>
                </td>
                <td>{{ formatDate(task.deadline) }}</td>
                <td>
                  <span v-if="task.users && task.users.length">
                    {{ task.users.map(u => u.name).join(', ') }}
                  </span>
                  <span v-else>
                    Chưa phân công
                  </span>
                </td>
                <td class="text-center">
                  <router-link :to="`/tasks/${task.id}`" class="btn btn-sm btn-info me-1">
                    <i class="bi bi-eye"></i>
                  </router-link>
                  <router-link :to="`/tasks/${task.id}/edit`" class="btn btn-sm btn-warning me-1">
                    <i class="bi bi-pencil"></i>
                  </router-link>
                  <button class="btn btn-sm btn-danger" @click="deleteTask(task.id)">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const tasks = ref([])
const filterStatus = ref('') // '' đại diện cho "Tất cả"

// Hàm lấy danh sách công việc từ API
const fetchTasks = async () => {
  try {
    const response = await axios.get('/api/tasks')
    // Giả sử API trả về dữ liệu dưới dạng response.data.data
    tasks.value = response.data.data
  } catch (error) {
    console.error('Lỗi tải danh sách công việc:', error)
  }
}

// Computed property lọc công việc theo trạng thái
const filteredTasks = computed(() => {
  if (!filterStatus.value) {
    return tasks.value
  }
  return tasks.value.filter(task => task.status === filterStatus.value)
})

// Hàm xóa công việc
const deleteTask = async (id) => {
  if (confirm('Bạn có chắc muốn xóa công việc này?')) {
    try {
      await axios.delete(`/api/tasks/${id}`)
      tasks.value = tasks.value.filter(task => task.id !== id)
    } catch (error) {
      alert(error.response?.data.message || 'Lỗi xóa công việc')
    }
  }
}

// Hàm chuyển đổi trạng thái thành nhãn thân thiện
const translateStatus = (status) => {
  switch (status) {
    case 'pending':
      return 'Chưa bắt đầu'
    case 'in_progress':
      return 'Đang thực hiện'
    case 'completed':
      return 'Đã hoàn thành'
    case 'canceled':
      return 'Đã hủy'
    default:
      return status
  }
}

// Hàm trả về lớp CSS dựa trên trạng thái
const getStatusClass = (status) => {
  switch (status) {
    case 'pending':
      return 'badge bg-secondary'
    case 'in_progress':
      return 'badge bg-info'
    case 'completed':
      return 'badge bg-success'
    case 'canceled':
      return 'badge bg-danger'
    default:
      return 'badge bg-light text-dark'
  }
}

// Hàm định dạng ngày tháng
const formatDate = (date) => {
  if (!date) return 'Không có'
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(date).toLocaleDateString('vi-VN', options)
}

onMounted(() => {
  fetchTasks()
})
</script>

<style scoped>
.container {
  max-width: 1200px;
  margin: auto;
}

.card-header.bg-gradient {
  background: linear-gradient(135deg, #4facfe, #00f2fe);
  padding: 1rem 1.5rem;
  border-bottom: 2px solid rgba(0, 0, 0, 0.1);
}

.table thead th {
  vertical-align: middle;
}

.table tbody td {
  vertical-align: middle;
}

.btn {
  font-size: 0.875rem;
}

.badge {
  font-size: 0.9rem;
  padding: 0.5em 0.75em;
}

@media (max-width: 576px) {
  .btn-group {
    flex-direction: column;
  }
}
</style>
