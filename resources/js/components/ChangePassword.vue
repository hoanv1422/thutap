<template>
    <div class="change-password-container">
        <h1 class="main-title">Thay Đổi Mật Khẩu</h1>

        <div v-if="loading" class="alert alert-info text-center">
            Đang tải...
        </div>
        <div v-else-if="error" class="alert alert-danger text-center">
            {{ error }}
        </div>

        <form v-else @submit.prevent="handleChangePassword" class="password-form card p-4">
            <div class="form-group">
                <label for="currentPassword" class="form-label">Mật khẩu hiện tại</label>
                <input type="password" id="currentPassword" v-model="passwordForm.currentPassword"
                    placeholder="Nhập mật khẩu hiện tại" class="form-control" required />
            </div>

            <div class="form-group">
                <label for="newPassword" class="form-label">Mật khẩu mới</label>
                <input type="password" id="newPassword" v-model="passwordForm.newPassword"
                    placeholder="Nhập mật khẩu mới" class="form-control" required />
            </div>

            <div class="form-group">
                <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                <input type="password" id="confirmPassword" v-model="passwordForm.newPassword_confirmation"
                    placeholder="Nhập lại mật khẩu mới" class="form-control" required />
            </div>


            <div v-if="passwordErrors.length" class="error">
                <ul>
                    <li v-for="(err, index) in passwordErrors" :key="index">{{ err }}</li>
                </ul>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-warning">Cập nhật mật khẩu</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/authStore';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const loading = ref(false);
const error = ref(null);
const passwordErrors = ref([]);

const passwordForm = ref({
    currentPassword: '',
    newPassword: '',
    newPassword_confirmation: '',
});

const handleChangePassword = async () => {
    passwordErrors.value = [];

    // Kiểm tra mật khẩu mới và xác nhận có khớp không
    if (passwordForm.value.newPassword !== passwordForm.value.newPassword_confirmation) {
        passwordErrors.value.push('Mật khẩu mới và xác nhận không khớp.');
        return;
    }


    loading.value = true;
    try {
        await authStore.updatePassword({
            currentPassword: passwordForm.value.currentPassword,
            newPassword: passwordForm.value.newPassword,
            newPassword_confirmation: passwordForm.value.newPassword_confirmation,
        });

        alert('Cập nhật mật khẩu thành công!');
        // Xóa form mật khẩu sau khi cập nhật
        passwordForm.value.currentPassword = '';
        passwordForm.value.newPassword = '';
        passwordForm.value.confirmPassword = '';
        router.push('/profile');
    } catch (err) {
        passwordErrors.value = [err.message || 'Lỗi cập nhật mật khẩu'];
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
.change-password-container {
    max-width: 500px;
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
    justify-content: center;
}

.error {
    color: red;
    margin-bottom: 20px;
    font-size: 1rem;
}

/* Custom style cho form card */
.password-form {
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