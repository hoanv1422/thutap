<!-- app.vue -->
<template>
    <div class="container-fluid px-0">
      <!-- Navigation Bar -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
          <router-link to="/dashboard" class="navbar-brand">
            <i class="bi bi-house-door me-2"></i>My App
          </router-link>
  
          <div class="d-flex align-items-center">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <!-- Authenticated Links -->
              <li class="nav-item" v-if="authStore.isAuthenticated">
                <router-link to="/dashboard" class="nav-link">Trang chủ</router-link>
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
  
              <!-- Avatar and Dropdown Menu -->
              <li class="nav-item dropdown" v-if="authStore.isAuthenticated">
                <a
                  class="nav-link dropdown-toggle d-flex align-items-center"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img
                    :src="authStore.user.avatar || 'https://via.placeholder.com/40'"
                    alt="Avatar"
                    class="rounded-circle me-2"
                    width="40"
                    height="40"
                  />
                  {{ authStore.user.name }}
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
      <main class="container mt-4">
        <router-view/>
      </main>
    </div>
  </template>
  
  <script setup>
  import { useAuthStore } from './stores/authStore'
  const authStore = useAuthStore()
  authStore.initialize()
  </script>
  
  <style scoped>
  .navbar {
    padding: 0.8rem 0;
  }
  
  .container {
    max-width: 1200px;
  }
  
  .nav-link {
    font-weight: 500;
    transition: all 0.3s ease;
  }
  
  .nav-link:hover {
    transform: translateY(-2px);
  }
  
  .btn-outline-light:hover {
    color: var(--bs-primary) !important;
  }
  
  .router-link-active {
    color: #fff !important;
    position: relative;
  }
  
  .router-link-active::after {
    content: "";
    position: absolute;
    bottom: -8px;
    left: 0;
    right: 0;
    height: 3px;
    background: #fff;
    border-radius: 2px;
  }
  </style>