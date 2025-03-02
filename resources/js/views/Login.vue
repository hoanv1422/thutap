<template>
  <div class="container">
    <div class="row justify-content-center vh-100 align-items-center">
      <div class="col-12 col-md-8 col-lg-6 col-xl-4">
        <div class="card shadow-lg border-0">
          <div class="card-body p-5">
            <div class="text-center mb-4">
              <img src="../assets/logo.png" alt="Logo" class="img-fluid mb-3" style="max-height: 60px">
              <h2 class="h3 mb-3 fw-normal">Đăng nhập hệ thống</h2>
            </div>
            <form @submit.prevent="handleLogin">
              <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Địa chỉ email</label>
                <input
                  type="email"
                  class="form-control form-control-lg"
                  id="email"
                  v-model="form.email"
                  placeholder="name@example.com"
                  required
                >
              </div>
              <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                <input
                  type="password"
                  class="form-control form-control-lg"
                  id="password"
                  v-model="form.password"
                  placeholder="••••••••"
                  required
                >
              </div>
              <div v-if="authStore.errors.length" class="alert alert-danger py-2">
                <ul class="mb-0">
                  <li v-for="(error, index) in authStore.errors" :key="index" class="small">
                    {{ error }}
                  </li>
                </ul>
              </div>
              <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Đăng nhập
              </button>
              <div class="text-center mt-4">
                <span class="text-muted">Chưa có tài khoản? </span>
                <router-link 
                  to="/register" 
                  class="text-decoration-none fw-semibold text-primary"
                >
                  Đăng ký ngay
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
  email: '',
  password: ''
})

const handleLogin = async () => {
  await authStore.login(form.value)
  if (authStore.isAuthenticated) {
    router.push('/dashboard')
  }
}
</script>

<style scoped>
.card {
  border-radius: 1rem;
  background: linear-gradient(145deg, #ffffff, #f8f9fa);
}

.form-control-lg {
  border-radius: 0.5rem;
  border: 2px solid #e9ecef;
  transition: all 0.3s ease;
}

.form-control-lg:focus {
  border-color: #86b7fe;
  box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
}

.btn-primary {
  background: #0d6efd;
  border-radius: 0.5rem;
  padding: 0.75rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background: #0b5ed7;
  transform: translateY(-1px);
}

.alert-danger {
  border-radius: 0.5rem;
}
</style>