import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import { useNotificationsStore } from '@/stores/notificationsStore'

window.Pusher = Pusher

const echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  forceTLS: true,
  encrypted: true,
  authEndpoint: '/broadcasting/auth',
  auth: {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('sanctum_token')}`
    }
  },
  wsHost: `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
  disableStats: true,
  enabledTransports: ['ws', 'wss']
})

echo.connector.pusher.connection.bind('connected', () => {
  console.log('Connected to Pusher!')
})

echo.connector.pusher.connection.bind('error', (err) => {
  console.error('Pusher connection error:', err)
})

window.Echo = echo

export function subscribeToNotifications(userId) {
  console.log(`Subscribing to user.${userId}`)
  const notificationsStore = useNotificationsStore()

  echo.private(`user.${userId}`)
    .listen('.TaskAssigned', async (e) => {
      console.log('[Echo] TaskAssigned received:', e)
      // Khi có sự kiện, tải lại danh sách thông báo từ backend
      await notificationsStore.loadNotifications()
    })
  echo.private(`user.${userId}`)
    .listen('.TaskUpdated', async (e) => {
      console.log('[Echo] TaskUpdated received:', e);
      // Tải lại toàn bộ danh sách thông báo từ backend sau khi cập nhật
      await notificationsStore.loadNotifications();
    });
}

export default echo
