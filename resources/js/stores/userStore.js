import { defineStore } from 'pinia'
import axios from 'axios'

export const useUserStore = defineStore('user', {
    state: () => ({
        users: [],
        selectedUser: null,
        loading: false,
        error: null,
    }),

    actions: {
        async fetchUsers() {
            try {
                this.loading = true
                const { data } = await axios.get('/api/users')
                this.users = data
            } catch (error) {
                this.error = error.response?.data.message || 'Lỗi tải danh sách người dùng'
            } finally {
                this.loading = false
            }
        },

        async fetchUserById(userId) {
            try {
                this.loading = true
                this.error = null
                const { data } = await axios.get(`/api/users/${userId}`)
                this.selectedUser = data
            } catch (error) {
                this.error = error.response?.data.message || 'Không tìm thấy người dùng'
                this.selectedUser = null
            } finally {
                this.loading = false
            }
        },

        async updateUser(userId, userData) {
            this.loading = true;
            try {
                // Sử dụng POST thay vì PUT
                const { data } = await axios.post(`/api/users/${userId}`, userData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                this.selectedUser = data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Lỗi cập nhật người dùng';
                throw new Error(this.error);
            } finally {
                this.loading = false;
            }
        }
        ,
        async deleteUser(userId) {
            try {
                await axios.delete(`/api/users/${userId}`)
                this.users = this.users.filter(user => user.id !== userId)
            } catch (error) {
                throw new Error(error.response?.data.message || 'Lỗi xóa người dùng')
            }
        }
    }
})
