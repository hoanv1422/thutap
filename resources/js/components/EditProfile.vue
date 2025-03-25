<template>
  <div class="edit-profile">
    <h1 class="main-title">Chỉnh sửa thông tin cá nhân</h1>

    <!-- Thông báo trạng thái -->
    <div v-if="loading" class="alert alert-info text-center">Đang tải...</div>
    <div v-else-if="error" class="alert alert-danger text-center">{{ error }}</div>

    <!-- Form cập nhật thông tin cá nhân -->
    <form v-else @submit.prevent="handleSubmit" class="profile-form card p-4">
      <div class="form-group">
        <label for="name" class="form-label">Tên</label>
        <input
          type="text"
          id="name"
          v-model="form.name"
          placeholder="Nhập tên"
          class="form-control"
          required
        />
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input
          type="email"
          id="email"
          v-model="form.email"
          class="form-control"
          disabled
        />
      </div>

      <!-- Hiển thị lỗi chung của form -->
      <div v-if="errors.length" class="error">
        <ul>
          <li v-for="(err, index) in errors" :key="index">{{ err }}</li>
        </ul>
      </div>

      <div class="actions">
        <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
        <router-link to="/profile" class="btn btn-secondary">Hủy</router-link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../stores/authStore';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const loading = ref(true);
const error = ref(null);
const errors = ref([]);

const form = ref({
  name: '',
  email: '',
  avatar: '',
});

// Khởi tạo form với dữ liệu hiện tại của user
onMounted(() => {
  if (authStore.user) {
    form.value.name = authStore.user.name;
    form.value.email = authStore.user.email;
    form.value.avatar = authStore.user.avatar || '';
  }
  loading.value = false;
});

// Xử lý cập nhật thông tin cá nhân
const handleSubmit = async () => {
  try {
    await authStore.updateProfile(form.value);
    alert('Cập nhật thông tin thành công!');
    router.push('/profile');
  } catch (err) {
    errors.value = [err.message || 'Lỗi cập nhật thông tin'];
  }
};
</script>

<style scoped>
.edit-profile {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.main-title {
  font-size: 2.2rem;
  margin-bottom: 1.5rem;
  text-align: center;
}

.form-group {
  margin-bottom: 20px;
}

.form-label {
  display: block;
  margin-bottom: 5px;
  font-weight: 600;
  font-size: 1.1rem;
}

.form-control {
  width: 100%;
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.actions {
  display: flex;
  gap: 10px;
  margin-top: 20px;
}

.error {
  color: red;
  margin-bottom: 20px;
  font-size: 1rem;
}

/* Custom style cho form card */
.profile-form {
  border: 1px solid #e0e0e0;
  padding: 20px;
  border-radius: 10px;
  background-color: #fff;
  margin-bottom: 30px;
}

.btn {
  font-size: 1rem;
  padding: 10px 15px;
  border-radius: 5px;
}

@media (max-width: 576px) {
  .actions {
    flex-direction: column;
  }
}
</style>
