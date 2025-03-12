// tests/Unit/EditPost.spec.js
import { mount } from '@vue/test-utils'
import EditPost from '@/components/EditPost.vue'  // Điều chỉnh đường dẫn nếu cần
import axios from 'axios'
import { createRouter, createWebHistory } from 'vue-router'

jest.mock('axios')

// Helper: chờ các promise hoàn thành
const flushPromises = () => new Promise(resolve => setTimeout(resolve));

const routes = [
  { 
    path: '/', 
    name: 'home', 
    component: { template: '<div>Home</div>' }
  },
  {
    path: '/posts/:id',
    name: 'edit-post',
    component: EditPost
  },
  {
    path: '/posts/:id',
    name: 'post-detail',
    component: { template: '<div>Post Detail</div>' }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

describe('EditPost.vue', () => {
  test('loads post data on mount and submits updated post', async () => {
    // Đưa router về route edit-post với params id: '123'
    await router.push({ name: 'edit-post', params: { id: '123' } })
    await router.isReady()

    // Giả lập axios.get trả về dữ liệu bài viết ban đầu
    const postData = { title: 'Original Title', content: 'Original Content' }
    axios.get.mockResolvedValueOnce({ data: postData })

    const wrapper = mount(EditPost, {
      global: {
        plugins: [router],
        stubs: {
          'router-link': true
        }
      }
    })

    // Chờ onMounted hook hoàn thành (axios.get)
    await flushPromises()

    // Kiểm tra dữ liệu ban đầu được load vào form
    expect(wrapper.vm.form.title).toBe('Original Title')
    expect(wrapper.vm.form.content).toBe('Original Content')

    // Mô phỏng nhập liệu: cập nhật form
    const titleInput = wrapper.find('input')
    const contentTextarea = wrapper.find('textarea')
    await titleInput.setValue('Updated Title')
    await contentTextarea.setValue('Updated Content')

    // Giả lập phản hồi axios.put khi submit form
    axios.put.mockResolvedValueOnce({ data: {} })
    window.alert = jest.fn()

    // Submit form
    await wrapper.find('form').trigger('submit.prevent')
    await flushPromises()

    // Kiểm tra rằng axios.put được gọi với dữ liệu đúng
    expect(axios.put).toHaveBeenCalledWith('/api/posts/123', {
      title: 'Updated Title',
      content: 'Updated Content'
    })

    // Kiểm tra alert hiển thị thành công
    expect(window.alert).toHaveBeenCalledWith('Bài viết đã được cập nhật thành công!')

    // Kiểm tra router đã chuyển hướng đúng (đến /posts/123)
    expect(router.currentRoute.value.path).toBe('/posts/123')
  })
})
