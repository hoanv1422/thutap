import { createApp } from 'vue'
import { createPinia } from 'pinia'
import persistedState from 'pinia-plugin-persistedstate'
import App from './App.vue'
import router from './router'

// Import cấu hình Axios
import './bootstrap'

// Import Echo & hàm subscribe
import { subscribeToNotifications } from './echo'

// Import store
import { useAuthStore } from './stores/authStore'

// Import CSS
import 'bootstrap-icons/font/bootstrap-icons.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'

const app = createApp(App)

const pinia = createPinia()
// Sử dụng plugin persisted state cho Pinia
pinia.use(persistedState)

app.use(pinia)
app.use(router)

app.mount('#app')

// Kiểm tra user, nếu có ID thì subscribe
const authStore = useAuthStore()
authStore.initialize().then(() => {
    if (authStore.user?.id) {
        subscribeToNotifications(authStore.user.id)
    }
})
