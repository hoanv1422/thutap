<template>
  <div class="container mt-5">
    <h1 class="mb-4 text-center">Thêm mới Công việc</h1>
    <form @submit.prevent="handleSubmit" novalidate class="card p-4">
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
              rows="4"
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
          <!-- Phân công người dùng -->
          <div class="mb-3">
            <label class="form-label">Người được phân công</label>
            <div class="assigned-members mb-2">
              <span v-if="form.assigned_user_ids.length === 0">Chưa phân công</span>
              <span
                v-for="member in assignedMembers"
                :key="member.id"
                class="badge bg-info me-2 mb-2"
              >
                {{ member.name }}
                <button
                  type="button"
                  class="btn-close btn-close-white btn-sm ms-1"
                  aria-label="Remove"
                  @click="removeMember(member.id)"
                ></button>
              </span>
            </div>
            <!-- Dropdown để thêm thành viên mới -->
            <div class="input-group">
              <select v-model="newMemberId" class="form-select">
                <option value="" disabled selected>-- Chọn thành viên để thêm --</option>
                <option v-for="user in availableUsers" :key="user.id" :value="user.id">
                  {{ user.name }}
                </option>
              </select>
              <button
                type="button"
                class="btn btn-outline-primary"
                @click="addMember"
                :disabled="!newMemberId"
              >
                Thêm
              </button>
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
              rows="3"
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
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

// Form dữ liệu chỉnh sửa công việc
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

// Danh sách tất cả người dùng
const users = ref([])
// Đối tượng chứa lỗi
const errors = ref({})

// Để lưu ID của thành viên mới được chọn từ dropdown
const newMemberId = ref(null)

// Hàm lấy danh sách người dùng
const fetchUsers = async () => {
  try {
    const response = await axios.get('/api/users')
    users.value = response.data.data || response.data
  } catch (error) {
    console.error('Lỗi tải danh sách người dùng:', error)
  }
}

// Hàm lấy dữ liệu công việc cần tạo (nếu có dữ liệu mẫu từ backend)
// Nếu đây là trang tạo mới, thường không có dữ liệu công việc để tải, nhưng bạn có thể có trường hợp chỉnh sửa mẫu.
const fetchTask = async () => {
  try {
    const response = await axios.get(`/api/tasks/${taskId}`)
    const data = response.data.data
    form.value = {
      name: data.name,
      description: data.description,
      start_time: data.start_time,
      deadline: data.deadline,
      status: data.status,
      priority: data.priority,
      progress: data.progress,
      assigned_user_ids: data.users ? data.users.map(u => u.id) : [],
      notes: data.notes,
    }
  } catch (error) {
    // Trong trang tạo mới, có thể bỏ qua nếu không có dữ liệu mẫu
    console.error('Lỗi khi tải dữ liệu công việc:', error)
  }
}

// Computed property trả về danh sách thành viên đã được phân công
const assignedMembers = computed(() => {
  return users.value.filter(user => form.value.assigned_user_ids.includes(user.id))
})

// Computed property trả về danh sách thành viên chưa được phân công
const availableUsers = computed(() => {
  return users.value.filter(user => !form.value.assigned_user_ids.includes(user.id))
})

// Hàm xoá một thành viên khỏi danh sách phân công
const removeMember = (id) => {
  form.value.assigned_user_ids = form.value.assigned_user_ids.filter(memberId => memberId !== id)
}

// Hàm thêm thành viên mới vào danh sách phân công
const addMember = () => {
  if (newMemberId.value && !form.value.assigned_user_ids.includes(newMemberId.value)) {
    form.value.assigned_user_ids.push(newMemberId.value)
    newMemberId.value = null // Reset selection
    console.log('Sau khi thêm:', JSON.parse(JSON.stringify(form.value)))
  }
}

// Hàm submit form tạo công việc
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

onMounted(() => {
  fetchUsers()
  // Nếu là trang tạo mới, bạn có thể không gọi fetchTask()
  // fetchTask()
})
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: auto;
}

.assigned-members {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.badge {
  font-size: 0.9rem;
  padding: 0.5em 0.75em;
  display: flex;
  align-items: center;
}

.btn-close {
  margin-left: 0.5rem;
  cursor: pointer;
}

/* Style cho form card */
.card {
  border: none;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  background-color: #fff;
  padding: 20px;
}

.form-label {
  font-weight: 600;
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
}

.form-control,
.form-select {
  padding: 10px;
  font-size: 1rem;
  border-radius: 5px;
}

.actions {
  display: flex;
  gap: 10px;
  margin-top: 20px;
  justify-content: flex-end;
}

.btn {
  font-size: 1rem;
  padding: 10px 15px;
  border-radius: 5px;
}

@media (max-width: 576px) {
  .actions {
    flex-direction: column;
  }
}
</style>
