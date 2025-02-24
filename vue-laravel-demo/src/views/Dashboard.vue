<template>
    <div class="dashboard">
      <div class="header">
        <h1>Welcome, {{ auth.user?.name }}!</h1>
        <button @click="handleLogout" class="btn btn-danger">Logout</button>
      </div>
      
      <div class="content">
        <UserList />
      </div>
    </div>
  </template>
  
  <script>
  import { useAuthStore } from '../stores/auth';
  import UserList from '../components/Users/UserList.vue';
  
  export default {
    components: {
      UserList
    },
    setup() {
      const auth = useAuthStore();
      return { auth };
    },
    methods: {
      handleLogout() {
        this.auth.logout();
        this.$router.push('/login');
      }
    }
  };
  </script>
  
  <style scoped>
  .dashboard {
    padding: 20px;
  }
  
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  </style>