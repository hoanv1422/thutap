<template>
    <div>
        <h1>Danh Sách Người Dùng</h1>
        <div v-if="userStore.loading">Đang tải...</div>
        <div v-else-if="userStore.error" class="error">{{ userStore.error }}</div>
        <div v-else>
            <table>
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Vai trò</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in userStore.users" :key="user.id">
                        <td>
                            <img v-if="user.avatar" :src="user.avatar" alt="Avatar" class="avatar" />
                            <span v-else>Chưa có</span>
                        </td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.phone || 'N/A' }}</td>
                        <td>{{ user.role }}</td>
                        <td>
                            <router-link :to="`/users/${user.id}`" class="view-button">Xem</router-link>
                            <button @click="handleDelete(user.id)">Xóa</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { useUserStore } from '../stores/userStore'
import { onMounted } from 'vue'

const userStore = useUserStore()

onMounted(() => {
    userStore.fetchUsers()
})

const handleDelete = async (userId) => {
    if (confirm('Bạn chắc chắn muốn xóa người dùng này?')) {
        try {
            await userStore.deleteUser(userId)
            alert('Người dùng đã được xóa!')
        } catch (error) {
            alert(error.message)
        }
    }
}
</script>

<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th,
td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #4f46e5;
    color: white;
}

.avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.view-button {
    margin-right: 10px;
    text-decoration: none;
    color: blue;
}

button {
    background-color: #ff4d4d;
    color: white;
    border: none;
    padding: 5px 10px;
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