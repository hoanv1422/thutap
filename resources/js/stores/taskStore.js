import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useTaskStore = defineStore('task', {
  state: () => ({
    tasks: ref([]),
  }),

  actions: {
    // Lấy danh sách công việc từ API
    async fetchTasks() {
      try {
        const token = localStorage.getItem('token') // Lấy token từ localStorage
        const response = await axios.get('/api/tasks', {
          headers: { Authorization: `Bearer ${token}` }
        })
        this.tasks = response.data.data
      } catch (error) {
        console.error('Lỗi tải danh sách công việc:', error)
      }
    },

    // Thêm công việc mới
    async addTask(taskData) {
      try {
        const token = localStorage.getItem('token')
        const response = await axios.post('/api/tasks', taskData, {
          headers: { Authorization: `Bearer ${token}` }
        })
        this.tasks.push(response.data.data)
      } catch (error) {
        console.error('Lỗi thêm công việc:', error)
        throw new Error(error.response?.data.message || 'Lỗi thêm công việc')
      }
    },

    // Cập nhật công việc
    async updateTask(id, updatedData) {
      try {
        const token = localStorage.getItem('token')
        const response = await axios.put(`/api/tasks/${id}`, updatedData, {
          headers: { Authorization: `Bearer ${token}` }
        })
        const index = this.tasks.findIndex(task => task.id === id)
        if (index !== -1) {
          this.tasks[index] = response.data.data
        }
      } catch (error) {
        console.error('Lỗi cập nhật công việc:', error)
        throw new Error(error.response?.data.message || 'Lỗi cập nhật công việc')
      }
    },

    // Xóa công việc
    async deleteTask(id) {
      if (!confirm('Bạn có chắc muốn xóa công việc này?')) return
      try {
        const token = localStorage.getItem('token')
        await axios.delete(`/api/tasks/${id}`, {
          headers: { Authorization: `Bearer ${token}` }
        })
        this.tasks = this.tasks.filter(task => task.id !== id)
      } catch (error) {
        console.error('Lỗi xóa công việc:', error)
        throw new Error(error.response?.data.message || 'Lỗi xóa công việc')
      }
    },
  }
})
