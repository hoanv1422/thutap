<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-lg border-0">
          <div class="card-body p-4 p-sm-5">
            <h2 class="card-title text-center mb-4">Đăng ký tài khoản</h2>

            <form @submit.prevent="handleRegister">
              <!-- Tên đầy đủ -->
              <div class="mb-3">
                <label for="name" class="form-label">Họ và tên</label>
                <input
                  type="text"
                  class="form-control form-control-lg"
                  id="name"
                  v-model="form.name"
                  placeholder="Nhập họ tên"
                  required
                >
              </div>

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label">Địa chỉ email</label>
                <input
                  type="email"
                  class="form-control form-control-lg"
                  id="email"
                  v-model="form.email"
                  placeholder="name@example.com"
                  required
                >
              </div>

              <!-- Mật khẩu -->
              <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input
                  type="password"
                  class="form-control form-control-lg"
                  id="password"
                  v-model="form.password"
                  placeholder="••••••••"
                  required
                >
              </div>

              <!-- Xác nhận mật khẩu -->
              <div class="mb-4">
                <label for="confirm-password" class="form-label">Xác nhận mật khẩu</label>
                <input
                  type="password"
                  class="form-control form-control-lg"
                  id="confirm-password"
                  v-model="form.password_confirmation"
                  placeholder="••••••••"
                  required
                >
              </div>

              <!-- Hiển thị lỗi -->
              <div v-if="authStore.errors.length" class="alert alert-danger py-2">
                <ul class="mb-0">
                  <li 
                    v-for="(error, index) in authStore.errors" 
                    :key="index"
                    class="small"
                  >
                    {{ error }}
                  </li>
                </ul>
              </div>

              <!-- Nút đăng ký -->
              <button 
                type="submit" 
                class="w-100 btn btn-primary btn-lg mt-3"
                :disabled="authStore.loading"
              >
                <span v-if="authStore.loading" class="spinner-border spinner-border-sm" role="status"></span>
                {{ authStore.loading ? ' Đang xử lý...' : 'Đăng ký' }}
              </button>

              <!-- Liên kết đăng nhập -->
              <div class="text-center mt-4">
                <span class="text-muted">Đã có tài khoản? </span>
                <router-link 
                  to="/login" 
                  class="text-decoration-none fw-semibold text-primary"
                >
                  Đăng nhập ngay
                </router-link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/authStore'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const handleRegister = async () => {
  try {
    await authStore.register(form.value)
    if (authStore.isAuthenticated) {
      router.push('/dashboard')
    }
  } catch (error) {
    // Xử lý lỗi cụ thể nếu cần
  }
}
</script>

<style scoped>
.card {
  border-radius: 1rem;
  background: linear-gradient(145deg, #ffffff, #f8f9fa);
}

.form-control-lg {
  border-radius: 0.75rem;
  border: 2px solid #e9ecef;
  transition: all 0.3s ease;
}

.form-control-lg:focus {
  border-color: #86b7fe;
  box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
}

.btn-primary {
  background: #0d6efd;
  border: none;
  padding: 1rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background: #0b5ed7;
  transform: translateY(-1px);
}

.alert-danger {
  border-radius: 0.75rem;
}
</style>