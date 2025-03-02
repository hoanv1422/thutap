<template>
    <div>
      <h1>Danh Sách Bài Viết</h1>
      <router-link to="/posts/create" class="create-button">Thêm bài viết</router-link>
      <div v-if="postStore.loading">Đang tải...</div>
      <div v-else-if="postStore.error" class="error">{{ postStore.error }}</div>
      <div v-else>
        <div v-for="post in postStore.posts" :key="post.id" class="post">
          <h3>{{ post.title }}</h3>
          <p>{{ post.content }}</p>
          <small>Tác giả: {{ post.user.name }}</small>
          <div class="actions">
            <router-link :to="`/posts/${post.id}`" class="view-button">Xem chi tiết</router-link>
            <router-link :to="`/posts/${post.id}/edit`" class="edit-button">Sửa</router-link>
            <button @click="handleDelete(post.id)">Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { usePostStore } from '../stores/postStore';
  import { onMounted } from 'vue';
  
  const postStore = usePostStore();
  onMounted(() => {
    postStore.fetchPosts();
  });
  
  const handleDelete = async (postId) => {
    if (confirm('Bạn chắc chắn muốn xóa bài viết này?')) {
      try {
        await postStore.deletePost(postId);
        alert('Bài viết đã được xóa thành công!'); 
      } catch (error) {
        alert(error.message); 
      }
    }
  };
  </script>
  
  <style scoped>
  .create-button {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 15px;
    background-color: #4f46e5;
    color: white;
    text-decoration: none;
    border-radius: 4px;
  }
  
  .create-button:hover {
    background-color: #4338ca;
  }
  
  .post {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
  }
  
  .post h3 {
    margin: 0 0 10px;
    font-size: 20px;
    color: #333;
  }
  
  .post p {
    margin: 0 0 10px;
    font-size: 16px;
    color: #555;
  }
  
  .post small {
    display: block;
    margin-bottom: 10px;
    font-size: 14px;
    color: #777;
  }
  
  .actions {
    display: flex;
    gap: 10px;
  }
  
  .view-button,
  .edit-button {
    padding: 5px 10px;
    background-color: #4f46e5;
    color: white;
    text-decoration: none;
    border-radius: 4px;
  }
  
  .view-button:hover,
  .edit-button:hover {
    background-color: #4338ca;
  }
  
  button {
    background-color: #ff4d4d;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #cc0000;
  }
  
  .error {
    color: red;
    font-weight: bold;
  }
  </style>