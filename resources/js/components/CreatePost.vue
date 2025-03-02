<template>
  <div class="create-post">
    <h2>Tạo Bài Viết Mới</h2>
    <form @submit.prevent="handleSubmit">
      <div class="form-group">
        <label>Tiêu đề</label>
        <input v-model="form.title" placeholder="Nhập tiêu đề" />
      </div>
      <div class="form-group">
        <label>Nội dung</label>
        <textarea v-model="form.content" placeholder="Nhập nội dung"></textarea>
      </div>
      <button type="submit">Đăng bài</button>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue';
import { usePostStore } from '../stores/postStore';
import { useRouter } from 'vue-router'; 

const form = reactive({
  title: '',
  content: ''
});

const postStore = usePostStore();
const router = useRouter(); 

const handleSubmit = async () => {
  try {
    await postStore.createPost(form);
    alert('Bài viết đã được đăng thành công!');
    form.title = '';
    form.content = '';
    await postStore.fetchPosts();
    router.push('/posts');
  } catch (error) {
    alert(error.message);
  }
};
</script>

<style scoped>
.create-post {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
  width: 100%;
  padding: 10px;
  background-color: #4f46e5;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
}

button:hover {
  background-color: #4338ca;
}
</style>