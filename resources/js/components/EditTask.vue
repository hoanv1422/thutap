<template>
  <div class="container mt-5">
    <h1 class="mb-4">Chỉnh sửa Công việc</h1>
    <form @submit.prevent="handleSubmit" novalidate class="card p-4">
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
        <div v-if="errors.name" class="text-danger mt-1">{{ errors.name[0] }}</div>
      </div>
      
      <!-- Mô tả -->
      <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea
          id="description"
          v-model="form.description"
          class="form-control"
          placeholder="Nhập mô tả công việc"
          rows="4"
        ></textarea>
        <div v-if="errors.description" class="text-danger mt-1">{{ errors.description[0] }}</div>
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
        <div v-if="errors.deadline" class="text-danger mt-1">{{ errors.deadline[0] }}</div>
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
        <div v-if="errors.status" class="text-danger mt-1">{{ errors.status[0] }}</div>
      </div>
      
      <!-- Danh sách Người được phân công -->
      <div class="mb-3">
        <label class="form-label">Người được phân công</label>
        <div class="assigned-members mb-2">
          <span v-if="form.assigned_user_ids.length === 0">Chưa phân công</span>
          <span v-for="member in assignedMembers" :key="member.id" class="badge bg-info me-2 mb-2">
            {{ member.name }}
            <button type="button" class="btn-close btn-close-white btn-sm ms-1" aria-label="Remove" @click="removeMember(member.id)"></button>
          </span>
        </div>
        <!-- Dropdown thêm thành viên mới -->
        <div class="input-group">
          <select v-model="newMemberId" class="form-select">
            <option value="" disabled selected>-- Chọn thành viên để thêm --</option>
            <option v-for="user in availableUsers" :key="user.id" :value="user.id">
              {{ user.name }}
            </option>
          </select>
          <button type="button" class="btn btn-outline-primary" @click="addMember" :disabled="!newMemberId">
            Thêm
          </button>
        </div>
        <div v-if="errors.assigned_user_ids" class="text-danger mt-1">{{ errors.assigned_user_ids[0] }}</div>
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
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const router = useRouter()
const taskId = route.params.id

// Form dữ liệu chỉnh sửa công việc
const form = ref({
  name: '',
  description: '',
  deadline: '',
  status: 'pending',
  assigned_user_ids: [],
})

// Danh sách người dùng
const users = ref([])
// Đối tượng chứa lỗi
const errors = ref({})

// Để chọn thành viên mới từ dropdown
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
      assigned_user_ids: data.users ? data.users.map(u => u.id) : [],
    }
  } catch (error) {
    console.error('Lỗi khi tải dữ liệu công việc:', error)
    alert('Lỗi khi tải dữ liệu công việc')
  }
}


const assignedMembers = computed(() => {
  return users.value.filter(user => form.value.assigned_user_ids.includes(user.id))
})

g
const availableUsers = computed(() => {
  return users.value.filter(user => !form.value.assigned_user_ids.includes(user.id))
})


const removeMember = (id) => {
  form.value.assigned_user_ids = form.value.assigned_user_ids.filter(memberId => memberId !== id)
}


const addMember = () => {
  if (newMemberId.value && !form.value.assigned_user_ids.includes(newMemberId.value)) {
    form.value.assigned_user_ids.push(newMemberId.value)
    newMemberId.value = null // Reset selection
  }
}

// Hàm submit form cập nhật công việc
const handleSubmit = async () => {
  errors.value = {} // Reset lỗi
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
