<template>
    <div class="post-detail">
      <h1>Chi tiết Bài viết</h1>
      <div v-if="loading">Đang tải...</div>
      <div v-else-if="error" class="error">{{ error }}</div>
      <div v-else>
        <h2>{{ post.title }}</h2>
        <p>{{ post.content }}</p>
        <small>Tác giả: {{ post.user.name }}</small>
        <router-link :to="`/posts/${post.id}/edit`" class="edit-button">Sửa bài viết</router-link>
        <router-link to="/posts" class="back-button">Quay lại</router-link>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router';
  import axios from 'axios';
  
  const route = useRoute();
  const post = ref(null);
  const loading = ref(true);
  const error = ref(null);
  
  onMounted(async () => {
    try {
      const response = await axios.get(`/api/posts/${route.params.id}`);
      post.value = response.data;
    } catch (err) {
      error.value = err.response?.data.message || 'Lỗi tải chi tiết bài viết';
    } finally {
      loading.value = false;
    }
  });
  </script>
  
  <style scoped>
  .post-detail {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
  }
  
  .post-detail h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
  }
  
  .post-detail p {
    font-size: 16px;
    color: #555;
    margin-bottom: 10px;
  }
  
  .post-detail small {
    display: block;
    font-size: 14px;
    color: #777;
    margin-bottom: 20px;
  }
  
  .edit-button,
  .back-button {
    display: inline-block;
    margin-right: 10px;
    padding: 10px 15px;
    background-color: #4f46e5;
    color: white;
    text-decoration: none;
    border-radius: 4px;
  }
  
  .edit-button:hover,
  .back-button:hover {
    background-color: #4338ca;
  }
  
  .error {
    color: red;
    font-weight: bold;
  }
  </style>