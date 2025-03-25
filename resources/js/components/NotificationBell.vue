<template>
  <div class="notification-bell" @click="toggleDropdown">
    <i class="bi bi-bell"></i>
    <!-- Hiển thị số thông báo chưa đọc dựa trên filteredNotifications -->
    <span v-if="unreadCount" class="badge">{{ unreadCount }}</span>
    <div v-if="showDropdown" class="dropdown-menu show">
      <div v-if="filteredNotifications.length === 0" class="dropdown-item text-muted">
        Không có thông báo nào
      </div>
      <div
        v-for="(notification, index) in filteredNotifications"
        :key="notification.id || index"
        class="dropdown-item notification-item"
        :class="[notification.type, { read: notification.read }]"
      >
        <div class="notification-header">
          <strong>{{ notification.title || 'Thông báo mới' }}</strong>
          <small>{{ formatTime(notification.created_at || notification.timestamp) }}</small>
        </div>
        <div class="notification-body">
          {{ notification.message }}
        </div>
        <div v-if="notification.details && Object.keys(notification.details).length" class="notification-details">
          <p><strong>Mô tả:</strong> {{ notification.details.description || 'Không có mô tả' }}</p>
          <p><strong>Deadline:</strong> {{ formatDate(notification.details.deadline) || 'Chưa xác định' }}</p>
          <p><strong>Ưu tiên:</strong> {{ notification.details.priority || 'Không xác định' }}</p>
          <p><strong>Tiến độ:</strong> {{ notification.details.progress }}%</p>
          <p><strong>Ghi chú:</strong> {{ notification.details.notes || 'Không có ghi chú' }}</p>
        </div>
        <button class="close-btn" @click.stop="remove(index)">&times;</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useNotificationsStore } from '@/stores/notificationsStore'
import { useAuthStore } from '@/stores/authStore'
import echo from '../echo'

const notificationsStore = useNotificationsStore()
const { notifications } = storeToRefs(notificationsStore)

const authStore = useAuthStore()
const { user } = storeToRefs(authStore)

const showDropdown = ref(false)

// Lọc các thông báo chỉ của user hiện tại
const filteredNotifications = computed(() => {
  if (user.value && user.value.id) {
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

const currentSubscriptionUserId = ref(null)

// Subscribe kênh realtime cho user hiện tại
function subscribeChannel(userId) {
  if (currentSubscriptionUserId.value && currentSubscriptionUserId.value !== userId) {
    notificationsStore.clearNotifications()
    echo.leave(`private-user.${currentSubscriptionUserId.value}`)
  }
  currentSubscriptionUserId.value = userId
  
  echo.private(`user.${userId}`)
    .listen('.TaskAssigned', async (e) => {
      console.log('Realtime event - TaskAssigned:', e)
      await notificationsStore.loadNotifications()
    })
    .listen('.TaskUpdated', async (e) => {
      console.log('Realtime event - TaskUpdated:', e)
      await notificationsStore.loadNotifications()
    })
}

onMounted(() => {
  if (user.value && user.value.id) {
    notificationsStore.loadNotifications()
    subscribeChannel(user.value.id)
  }
})

watch(user, (newUser, oldUser) => {
  if (newUser && newUser.id) {
    if (!oldUser || newUser.id !== oldUser.id) {
      notificationsStore.clearNotifications()
      subscribeChannel(newUser.id)
      notificationsStore.loadNotifications()
    }
  }
})

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value
  if (showDropdown.value) {
    // Khi mở dropdown, đánh dấu tất cả thông báo là đã đọc trên backend
    notificationsStore.markNotificationsAsRead()
  }
}

const remove = (index) => {
  notificationsStore.notifications.splice(index, 1)
}
</script>

<style scoped>
.notification-bell {
  position: relative;
  cursor: pointer;
  font-size: 1.5rem;
  color: #fff;
  margin-right: 1rem;
}
.notification-bell .badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: red;
  color: #fff;
  border-radius: 50%;
  padding: 2px 6px;
  font-size: 0.75rem;
}
.dropdown-menu {
  position: absolute;
  top: 110%;
  right: 0;
  width: 350px;
  max-height: 400px;
  overflow-y: auto;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  z-index: 1000;
  padding: 0.5rem;
}
.notification-item {
  position: relative;
  border-bottom: 1px solid #eee;
  padding: 0.5rem;
  transition: background 0.3s;
}
.notification-item:hover {
  background: #f9f9f9;
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
  margin-top: 0.25rem;
  font-size: 0.9rem;
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
