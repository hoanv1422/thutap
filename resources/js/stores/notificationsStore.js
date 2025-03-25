import { defineStore } from 'pinia'
import axios from 'axios'

export const useNotificationsStore = defineStore('notifications', {
  state: () => ({
    notifications: []
  }),
  actions: {
    /**
     * Tải các thông báo của user hiện tại từ backend.
     * GET /api/notifications
     */
    async loadNotifications() {
      try {
        const response = await axios.get('/api/notifications')
        if (response.data && response.data.data) {
          this.notifications = response.data.data
        } else {
          console.error('Response không hợp lệ:', response.data)
        }
      } catch (error) {
        console.error('Lỗi load thông báo:', error)
      }
    },
    /**
     * Cập nhật trạng thái đã đọc của tất cả thông báo
     * POST /api/notifications/mark-read
     */
    async markNotificationsAsRead() {
      try {
        await axios.post('/api/notifications/mark-read')
        this.notifications = this.notifications.map(notification => ({
          ...notification,
          read: true
        }))
      } catch (error) {
        console.error('Lỗi cập nhật trạng thái đã đọc:', error)
      }
    },
    /**
     * Xoá hết thông báo trong store
     */
    clearNotifications() {
      this.notifications = []
    }
  },
  persist: true // Nếu bạn muốn lưu vào localStorage
})
