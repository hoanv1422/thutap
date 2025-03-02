<!-- <template>
    <div>
      <div v-if="postStore.loading">Đang tải...</div>
      <div v-else-if="postStore.error" class="error">{{ postStore.error }}</div>
      <div v-else>
        <div v-for="post in postStore.posts" :key="post.id" class="post">
          <h3>{{ post.title }}</h3>
          <p>{{ post.content }}</p>
          <small>Tác giả: {{ post.user.name }}</small>
          <button @click="handleDelete(post.id)">Xóa</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { usePostStore } from '../stores/postStore';
  import { onMounted } from 'vue';
  
  const postStore = usePostStore();
  
  // Fetch danh sách bài viết khi component được tạo
  onMounted(() => {
    postStore.fetchPosts();
  });
  
  const handleDelete = async (postId) => {
    if (confirm('Bạn chắc chắn muốn xóa?')) {
      await postStore.deletePost(postId);
    }
  };
  </script>
  
  <style scoped>
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
  
  .post button {
    background-color: #ff4d4d;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .post button:hover {
    background-color: #cc0000;
  }
  
  .error {
    color: red;
    font-weight: bold;
  }
  </style> -->