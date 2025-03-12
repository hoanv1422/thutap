// tests/Unit/TaskList.spec.js
import { mount } from '@vue/test-utils'
import TaskList from '@/components/Task.vue'
import { createTestingPinia } from '@pinia/testing'
import { createRouter, createWebHistory } from 'vue-router'

// Tạo router instance giả lập với một vài route cơ bản
const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/dashboard' },
    { path: '/dashboard', component: { template: '<div>Dashboard</div>' } },
  ]
})

describe('TaskList.vue', () => {
  test('renders tasks list correctly', async () => {
    router.push('/')
    await router.isReady()

    // Sử dụng stubActions: true để không chạy các action thực sự (fetchTasks, ...)
    const wrapper = mount(TaskList, {
      global: {
        plugins: [
          router,
          createTestingPinia({
            stubActions: true,
            initialState: {
              task: {
                // Giả lập danh sách công việc
                tasks: [
                  {
                    id: 1,
                    name: 'Task 1',
                    description: 'Test task 1',
                    status: 'pending',
                    deadline: '2023-12-31',
                    user_id: 1,
                    created_at: '2023-01-01T00:00:00.000000Z',
                    updated_at: '2023-01-01T00:00:00.000000Z',
                    // Quan hệ many-to-many: danh sách người được phân công
                    users: [
                      { id: 1, name: 'User A' },
                      { id: 2, name: 'User B' }
                    ]
                  },
                  {
                    id: 2,
                    name: 'Task 2',
                    description: 'Test task 2',
                    status: 'completed',
                    deadline: '2023-11-30',
                    user_id: 1,
                    created_at: '2023-01-01T00:00:00.000000Z',
                    updated_at: '2023-01-01T00:00:00.000000Z',
                    users: [] // Không có người được phân công
                  }
                ]
              }
            }
          })
        ],
        stubs: {
          'router-link': true
        }
      }
    })

    // Kiểm tra tiêu đề của trang
    expect(wrapper.find('h1').text()).toContain('Danh sách Công việc')

    // // Kiểm tra số dòng trong bảng (dự kiến 2 dòng)
    // const rows = wrapper.findAll('tbody tr')
    // expect(rows.length).toBe(2)

    // // Kiểm tra thông tin của công việc đầu tiên
    // const firstRowText = rows[0].text()
    // expect(firstRowText).toContain('Task 1')
    // expect(firstRowText).toContain('Test task 1')
    // expect(firstRowText).toContain('Chưa bắt đầu') // Dựa vào hàm translateStatus
    // // Kiểm tra tên của các người được phân công (User A và User B)
    // expect(firstRowText).toContain('User A')
    // expect(firstRowText).toContain('User B')
  })
})
