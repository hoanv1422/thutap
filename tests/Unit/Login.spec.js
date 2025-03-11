// tests/Unit/Login.spec.js
import { mount } from '@vue/test-utils'
import Login from '@/views/Login.vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createTestingPinia } from '@pinia/testing'
import { useAuthStore } from '@/stores/authStore'

// Tạo router instance với route mặc định
const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/dashboard' },
    { path: '/dashboard', name: 'dashboard', component: { template: '<div>Dashboard</div>' } }
  ]
})

describe('Login.vue', () => {
  test('renders login form and handles login', async () => {
    router.push('/')
    await router.isReady()

    // Mount component với các plugin cần thiết
    const wrapper = mount(Login, {
      global: {
        plugins: [router, createTestingPinia({ stubActions: false })],
        stubs: {
          'router-link': true
        }
      }
    })

    // Kiểm tra tiêu đề của trang đăng nhập
    expect(wrapper.find('h2').text()).toContain('Đăng nhập hệ thống')

    // Mô phỏng nhập liệu
    await wrapper.find('input#email').setValue('test@example.com')
    await wrapper.find('input#password').setValue('password123')

    // Lấy store instance từ testing pinia
    const authStore = useAuthStore()
    // Spy hành động login (nếu chưa được stub)
    const loginSpy = jest.spyOn(authStore, 'login')

    // Submit form
    await wrapper.find('form').trigger('submit.prevent')

    // Kiểm tra rằng hành động login được gọi với dữ liệu đúng
    expect(loginSpy).toHaveBeenCalledWith({
      email: 'test@example.com',
      password: 'password123'
    })

    // Giả lập login thành công: cập nhật trạng thái store
    authStore.isAuthenticated = true

    // Kiểm tra chuyển hướng: router phải chuyển sang route '/dashboard'
    expect(router.currentRoute.value.path).toBe('/dashboard')
  })
})
