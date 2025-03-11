// tests/Unit/Register.spec.js
import { mount } from '@vue/test-utils'
import Register from '@/views/Register.vue'
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

describe('Register.vue', () => {
  test('renders register form and handles registration', async () => {
    router.push('/')
    await router.isReady()

    const wrapper = mount(Register, {
      global: {
        plugins: [router, createTestingPinia({ stubActions: false })],
        stubs: {
          'router-link': true
        }
      }
    })

    // Kiểm tra tiêu đề của trang đăng ký
    expect(wrapper.find('h2').text()).toContain('Đăng ký tài khoản')

    // Mô phỏng nhập liệu cho các trường của form
    await wrapper.find('input#name').setValue('Test User')
    await wrapper.find('input#email').setValue('test@example.com')
    await wrapper.find('input#password').setValue('password123')
    await wrapper.find('input#confirm-password').setValue('password123')

    const authStore = useAuthStore()
    const registerSpy = jest.spyOn(authStore, 'register')

    // Submit form
    await wrapper.find('form').trigger('submit.prevent')

    // Kiểm tra hành động register được gọi với dữ liệu đúng
    expect(registerSpy).toHaveBeenCalledWith({
      name: 'Test User',
      email: 'test@example.com',
      password: 'password123',
      password_confirmation: 'password123'
    })

    // Giả lập đăng ký thành công
    authStore.isAuthenticated = true

    // Kiểm tra chuyển hướng router sang dashboard
    expect(router.currentRoute.value.path).toBe('/dashboard')
  })
})
