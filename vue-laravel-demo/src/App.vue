<template>
  <div id="app">
    <nav class="main-nav" v-if="auth.user">
      <div class="container">
        <router-link to="/" class="brand">VueLaravelDemo</router-link>
        <div class="nav-links">
          <router-link to="/dashboard">Dashboard</router-link>
          <button @click="auth.logout" class="btn btn-link">Logout</button>
        </div>
      </div>
    </nav>
    
    <router-view/>
    
    <footer class="main-footer">
      <div class="container">
        <p>&copy; 2024 Vue-Laravel Demo. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script>
import { useAuthStore } from './stores/auth'

export default {
  setup() {
    const auth = useAuthStore()
    return { auth }
  }
}
</script>

<style>
#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-nav {
  background: #2c3e50;
  padding: 1rem 0;
  color: white;
}

.main-nav .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.brand {
  font-size: 1.5rem;
  font-weight: bold;
  color: white;
  text-decoration: none;
}

.nav-links a {
  color: white;
  margin-left: 2rem;
  text-decoration: none;
}

.main-footer {
  margin-top: auto;
  background: #34495e;
  color: white;
  padding: 2rem 0;
  text-align: center;
}

.container {
  width: 90%;
  max-width: 1200px;
  margin: 0 auto;
}
</style>