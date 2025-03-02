import { defineStore } from 'pinia';
import axios from 'axios';

export const usePostStore = defineStore('post', {
  state: () => ({
    posts: [],
    loading: false,
    error: null
  }),
  actions: {
    async checkAuth() {
        try {
          const response = await axios.get('/api/user');
          this.user = response.data;
          this.isAuthenticated = true;
        } catch (error) {
          this.user = null;
          this.isAuthenticated = false;
        }
      },
    async fetchPosts() {
      try {
        this.loading = true;
        const { data } = await axios.get('/api/posts');
        this.posts = data;
      } catch (error) {
        this.error = error.response?.data.message || 'Lỗi tải bài viết';
      } finally {
        this.loading = false;
      }
    },
    async createPost(postData) {
        try {
          this.loading = true;
          const { data } = await axios.post('/api/posts', postData);
          this.posts.unshift(data);
        } catch (error) {
          throw new Error(error.response?.data.message || 'Lỗi tạo bài viết');
        } finally {
          this.loading = false;
        }
      },
      async updatePost(postId, postData) {
        try {
          const { data } = await axios.put(`/api/posts/${postId}`, postData);
          const index = this.posts.findIndex(post => post.id === postId);
          if (index !== -1) {
            this.posts.splice(index, 1, data);
          }
        } catch (error) {
          throw new Error(error.response?.data.message || 'Lỗi cập nhật bài viết');
        }
      },
    async deletePost(postId) {
      try {
        await axios.delete(`/api/posts/${postId}`);
        this.posts = this.posts.filter(post => post.id !== postId);
      } catch (error) {
        throw new Error(error.response?.data.message || 'Lỗi xóa bài viết');
      }
    }
  }
});