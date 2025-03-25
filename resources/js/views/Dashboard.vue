<template>
  <div class="home">
    <h1>Trang Chủ</h1>
    <p>Chào mừng bạn đến với trang quản lý Công việc</p>

    <!-- Phần Thông cáo công việc realtime -->
    <section class="announcements">
      <h2>Thông cáo công việc</h2>
      <div v-if="filteredNotifications.length === 0" class="alert alert-info">
        Không có thông báo mới.
      </div>
      <ul v-else class="list-group">
        <li
          v-for="(notification, index) in filteredNotifications"
          :key="notification.id || index"
          class="list-group-item notification-item"
          :class="[notification.type, { read: notification.read }]"
        >
          <div class="notification-header">
            <strong>{{ notification.title || 'Thông báo mới' }}</strong>
            <small>{{ formatTime(notification.created_at || notification.timestamp) }}</small>
          </div>
          <div class="notification-body">
            {{ notification.message }}
          </div>
          <!-- Thông tin chi tiết nếu có -->
          <div v-if="notification.details && Object.keys(notification.details).length" class="notification-details">
            <p><strong>Mô tả:</strong> {{ notification.details.description || 'Không có mô tả' }}</p>
            <p><strong>Deadline:</strong> {{ formatDate(notification.details.deadline) || 'Chưa xác định' }}</p>
            <p><strong>Ưu tiên:</strong> {{ notification.details.priority || 'Không xác định' }}</p>
            <p><strong>Tiến độ:</strong> {{ notification.details.progress }}%</p>
            <p><strong>Ghi chú:</strong> {{ notification.details.notes || 'Không có ghi chú' }}</p>
          </div>
          <button class="close-btn" @click.stop="remove(index)">&times;</button>
        </li>
      </ul>
    </section>

    <!-- Các phần khác: Giới thiệu, Liên hệ, ... -->
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useNotificationsStore } from '@/stores/notificationsStore'
import { useAuthStore } from '@/stores/authStore'
import echo, { subscribeToNotifications } from '../echo'

const notificationsStore = useNotificationsStore()
const { notifications } = storeToRefs(notificationsStore)

const authStore = useAuthStore()
const { user } = storeToRefs(authStore)

// Lọc các thông báo chỉ của user hiện tại
const filteredNotifications = computed(() => {
  if (user.value && user.value.id) {
    // API trả về user_id, ta so sánh với user.value.id
    return notifications.value.filter(note => note.user_id === user.value.id)
  }
  return []
})

// Tính số lượng thông báo chưa đọc
const unreadCount = computed(() =>
  filteredNotifications.value.filter(n => !n.read).length
)

const formatTime = (timestamp) => {
  const date = new Date(timestamp)
  return date.toLocaleTimeString()
}

const formatDate = (dateStr) => {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleDateString()
}

// Biến kiểm soát subscription cũ
const currentSubscriptionUserId = ref(null)

function subscribeChannel(userId) {
  if (currentSubscriptionUserId.value && currentSubscriptionUserId.value !== userId) {
    notificationsStore.clearNotifications()
    echo.leave(`private-user.${currentSubscriptionUserId.value}`)
  }
  currentSubscriptionUserId.value = userId
  // Gọi hàm subscribeToNotifications (từ echo.js)
  subscribeToNotifications(userId)
}

onMounted(() => {
  if (user.value && user.value.id) {
    // Load thông báo cũ
    notificationsStore.loadNotifications()
    // Subscribe kênh realtime
    subscribeChannel(user.value.id)
  }
})

watch(user, (newUser, oldUser) => {
  if (newUser && newUser.id && (!oldUser || newUser.id !== oldUser.id)) {
    notificationsStore.clearNotifications()
    subscribeChannel(newUser.id)
    notificationsStore.loadNotifications()
  }
})

// Khi bấm chuông, đánh dấu tất cả đã đọc và update backend
const showDropdown = ref(false)
const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value
  if (showDropdown.value) {
    notificationsStore.markNotificationsAsRead()
  }
}

function remove(index) {
  notificationsStore.notifications.splice(index, 1)
}
</script>

<style scoped>
.home {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  text-align: center;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

h1 {
  font-size: 2.5rem;
  color: #333;
  margin-bottom: 20px;
}

.announcements {
  margin-top: 30px;
  padding: 20px;
  background-color: #e9f5ff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  text-align: left;
}

.announcements h2 {
  font-size: 1.8rem;
  color: #007bff;
  margin-bottom: 15px;
}

.intro,
.contact {
  margin-top: 30px;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  text-align: left;
}

.intro h2,
.contact h2 {
  font-size: 1.8rem;
  color: #444;
  margin-bottom: 15px;
}

ul {
  list-style-type: none;
  padding: 0;
}

ul li {
  font-size: 1rem;
  color: #666;
  margin: 10px 0;
}

.list-group {
  margin-top: 15px;
  padding: 0;
}

.list-group-item {
  background-color: #fff;
  border: 1px solid #ddd;
  padding: 10px;
  margin-bottom: 8px;
  border-radius: 5px;
}

.notification-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.notification-header strong {
  color: #333;
}

.notification-header small {
  color: #888;
}

.notification-body {
  margin-top: 5px;
  color: #555;
}

.notification-details {
  margin-top: 10px;
  background-color: #f1f1f1;
  padding: 10px;
  border-radius: 4px;
  font-size: 0.9rem;
}

.notification-details p {
  margin: 5px 0;
}

.close-btn {
  position: absolute;
  top: 5px;
  right: 5px;
  background: transparent;
  border: none;
  font-size: 1rem;
  cursor: pointer;
}
</style>
