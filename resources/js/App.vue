<template>
  <div class="app-container">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg custom-navbar shadow-sm">
      <div class="container">
        <router-link to="/dashboard" class="navbar-brand custom-brand">
          <i class="bi bi-house-door me-2"></i> My App
        </router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
          aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
            <!-- Authenticated Links -->
            <li class="nav-item" v-if="authStore.isAuthenticated">
              <router-link to="/dashboard" class="nav-link">Trang chủ</router-link>
            </li>
            <li class="nav-item" v-if="authStore.isAuthenticated">
              <router-link to="/users" class="nav-link">Thành viên</router-link>
            </li>
            <li class="nav-item" v-if="authStore.isAuthenticated">
              <router-link to="/posts" class="nav-link">Bài viết</router-link>
            </li>
            <li class="nav-item" v-if="authStore.isAuthenticated">
              <router-link to="/tasks" class="nav-link">Công việc</router-link>
            </li>
            <!-- Guest Links -->
            <li class="nav-item" v-if="!authStore.isAuthenticated">
              <router-link to="/login" class="nav-link">Đăng nhập</router-link>
            </li>
            <li class="nav-item" v-if="!authStore.isAuthenticated">
              <router-link to="/register" class="nav-link">Đăng ký</router-link>
            </li>

            <!-- Notification Bell (Chèn component NotificationBell nếu có) -->
            <li class="nav-item" v-if="authStore.isAuthenticated">
              <NotificationBell />
            </li>

            <!-- Avatar and Dropdown Menu -->
            <li class="nav-item dropdown" v-if="authStore.isAuthenticated">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img :src="authStore.user.avatar || 'https://via.placeholder.com/40'" alt="Avatar"
                  class="rounded-circle me-2 avatar-img" />
                <span>{{ authStore.user.name }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                  <router-link to="/profile" class="dropdown-item">
                    <i class="bi bi-person me-2"></i>Thông tin cá nhân
                  </router-link>
                </li>
                <li>
                  <hr class="dropdown-divider" />
                </li>
                <li>
                  <button @click="authStore.logout" class="dropdown-item">
                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                  </button>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content container mt-4">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="footer bg-light py-3 mt-auto">
      <div class="container text-center">
        <small>&copy; {{ new Date().getFullYear() }} My App. All rights reserved.</small>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { useAuthStore } from './stores/authStore'
import NotificationBell from './components/NotificationBell.vue' // Nếu bạn có component chuông thông báo

const authStore = useAuthStore()
authStore.initialize()
</script>

<style scoped>
/* App Container */
.app-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* Custom Navbar */
.custom-navbar {
  /* Gradient màu xanh */
  background: linear-gradient(135deg, #4facfe, #00f2fe);
  padding: 0.75rem 0;
}

/* Tên thương hiệu */
.custom-brand {
  font-size: 1.4rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  color: #000 !important;
  /* Thay đổi màu chữ theo ý thích */
}

/* Navbar Links */
.navbar-nav {
  gap: 1rem;
  /* Khoảng cách giữa các mục menu */
}

.navbar-nav .nav-link {
  font-size: 1rem;
  transition: transform 0.3s ease, color 0.3s ease;
  color: #000 !important;
  /* Thay đổi màu chữ menu */
}

.navbar-nav .nav-link:hover {
  transform: translateY(-2px);
  color: #fff !important;
  /* Màu chữ khi hover */
}

/* NotificationBell (nếu có) */
.notification-bell {
  position: relative;
  cursor: pointer;
  font-size: 1.5rem;
  color: #000;
  margin-right: 0.5rem;
}

.notification-bell .badge {
  position: absolute;
  top: -5px;
  right: -10px;
  background: red;
  color: #fff;
  border-radius: 50%;
  padding: 2px 6px;
  font-size: 0.75rem;
}

/* Avatar Image */
.avatar-img {
  width: 40px;
  height: 40px;
  object-fit: cover;
}

/* Main Content */
.main-content {
  flex: 1;
}

/* Footer */
.footer {
  background-color: #f8f9fa;
}

/* Dropdown */
.dropdown-menu {
  min-width: 200px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
</style>
