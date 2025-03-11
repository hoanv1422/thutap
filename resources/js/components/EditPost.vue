<template>
    <div class="edit-post">
      <h1>Sửa Bài viết</h1>
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label>Tiêu đề</label>
          <input v-model="form.title" placeholder="Nhập tiêu đề" required />
        </div>
        <div class="form-group">
          <label>Nội dung</label>
          <textarea v-model="form.content" placeholder="Nhập nội dung" required></textarea>
        </div>
        <button type="submit">Lưu thay đổi</button>
        <router-link :to="`/posts/${postId}`" class="cancel-button">Hủy</router-link>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import axios from 'axios';
  
  const route = useRoute();
  const router = useRouter();
  const postId = route.params.id;
  const form = ref({ title: '', content: '' });
  
  onMounted(async () => {
    try {
      const response = await axios.get(`/api/posts/${postId}`);
      form.value = response.data;
    } catch (error) {
      alert('Lỗi tải bài viết để chỉnh sửa');
    }
  });
  
  const handleSubmit = async () => {
    try {
      await axios.put(`/api/posts/${postId}`, form.value);
      alert('Bài viết đã được cập nhật thành công!');
      router.push(`/posts/${postId}`);
    } catch (error) {
      alert(error.response?.data.message || 'Lỗi cập nhật bài viết');
    }
  };
  </script>
  
  <style scoped>
  .edit-post {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
  }
  
  .form-group {
    margin-bottom: 16px;
  }
  
  .form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
  }
  
  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
  }
  
  .form-group textarea {
    resize: vertical;
    min-height: 120px;
  }
  
  button {
    padding: 10px 15px;
    background-color: #4f46e5;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #4338ca;
  }
  
  .cancel-button {
    margin-left: 10px;
    padding: 10px 15px;
    background-color: #ff4d4d;
    color: white;
    text-decoration: none;
    border-radius: 4px;
  }
  
  .cancel-button:hover {
    background-color: #cc0000;
  }
  </style>