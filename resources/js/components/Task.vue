<template>
    <div class="container mt-5">
      <h1 class="mb-4">Danh sách Công việc</h1>
      <div class="mb-3 text-end">
        <router-link to="/tasks/create" class="btn btn-primary">
          Tạo Công việc Mới
        </router-link>
      </div>
  
      <div v-if="tasks.length === 0" class="alert alert-info">
        Bạn chưa có công việc nào.
      </div>
  
      <table v-else class="table table-bordered">
        <thead>
          <tr>
            <th>Tên công việc</th>
            <th>Mô tả</th>
            <th>Trạng thái</th>
            <th>Ngày hết hạn</th>
            <th>Người được phân công</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in tasks" :key="task.id">
            <td>{{ task.name }}</td>
            <td>{{ task.description }}</td>
            <td>
              <span :class="getStatusClass(task.status)">
                {{ translateStatus(task.status) }}
              </span>
            </td>
            <td>{{ formatDate(task.deadline) }}</td>
            <td>
              <!-- Nếu có danh sách người được phân công, hiển thị tên nối nhau, nếu không hiển thị "Chưa phân công" -->
              <span v-if="task.users && task.users.length">
                {{ task.users.map(user => user.name).join(', ') }}
              </span>
              <span v-else>
                Chưa phân công
              </span>
            </td>
            <td>
              <router-link :to="`/tasks/${task.id}`" class="btn btn-sm btn-info me-1">
                Chi tiết
              </router-link>
              <router-link :to="`/tasks/${task.id}/edit`" class="btn btn-sm btn-warning me-1">
                Chỉnh sửa
              </router-link>
              <button class="btn btn-sm btn-danger" @click="deleteTask(task.id)">
                Xóa
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  
  const tasks = ref([])
  
  // Lấy danh sách công việc từ API, yêu cầu API trả về eager loaded quan hệ "users"
  const fetchTasks = async () => {
    try {
      const response = await axios.get('/api/tasks')
      tasks.value = response.data.data
    } catch (error) {
      console.error('Lỗi tải danh sách công việc:', error)
    }
  }
  
  // Xóa công việc
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
  
  // Hàm chuyển đổi trạng thái thành nhãn tiếng Việt
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
        return 'status-label status-pending'
      case 'in_progress':
        return 'status-label status-in-progress'
      case 'completed':
        return 'status-label status-completed'
      case 'canceled':
        return 'status-label status-canceled'
      default:
        return 'status-label'
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
  
  .table th,
  .table td {
    vertical-align: middle;
  }
  
  .btn {
    font-size: 0.875rem;
  }
  
  /* Các lớp định dạng trạng thái */
  .status-label {
    display: inline-block;
    padding: 0.25em 0.5em;
    border-radius: 0.25em;
    color: #fff;
    font-size: 0.875rem;
    text-transform: capitalize;
  }
  .status-pending {
    background-color: #ffc107;
  }
  .status-in-progress {
    background-color: #17a2b8;
  }
  .status-completed {
    background-color: #28a745;
  }
  .status-canceled {
    background-color: #dc3545;
  }
  </style>
  