import { mount } from '@vue/test-utils'
import Register from '@/views/Register.vue'
import { createTestingPinia } from '@pinia/testing'
import { createRouter, createWebHistory } from 'vue-router'

// Tạo router instance với một vài route cần thiết cho test
const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/dashboard' },
    { path: '/dashboard', component: { template: '<div>Dashboard</div>' } },
    // Nếu cần thêm route khác, có thể thêm vào đây
  ]
})

describe('Register.vue', () => {
  test('renders register form', async () => {
    // Đưa router về trạng thái sẵn sàng
    router.push('/')
    await router.isReady()

    const wrapper = mount(Register, {
      global: {
        plugins: [
          router,
          createTestingPinia() // Cung cấp instance Pinia cho test
        ],
        stubs: {
          // Giả lập router-link để tránh cảnh báo
          'router-link': true
        }
      }
    })
    
    // Kiểm tra xem tiêu đề của form đăng ký có hiển thị không
    // (Thay 'h2' và nội dung theo template của bạn)
    expect(wrapper.find('h2').text()).toContain('Đăng ký tài khoản')
  })
})
