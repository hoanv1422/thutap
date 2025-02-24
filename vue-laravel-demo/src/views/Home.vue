<!-- src/views/Home.vue -->
<template>
    <div class="home-page">
      <!-- Hero Section -->
      <section class="hero bg-gradient">
        <div class="container">
          <div class="hero-content">
            <h1 class="hero-title">Welcome to Vue-Laravel Demo</h1>
            <p class="hero-subtitle">Build amazing fullstack applications with Vue 3 and Laravel</p>
            
            <div class="cta-buttons">
              <router-link 
                v-if="!auth.user"
                to="/login" 
                class="btn btn-primary btn-lg"
              >
                Get Started
              </router-link>
              <router-link 
                v-if="auth.user"
                to="/dashboard" 
                class="btn btn-success btn-lg"
              >
                Go to Dashboard
              </router-link>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Features Section -->
      <section class="features">
        <div class="container">
          <h2 class="section-title">Why Choose Us?</h2>
          
          <div class="features-grid">
            <div class="feature-card">
              <div class="feature-icon">
                <i class="fas fa-rocket"></i>
              </div>
              <h3>Fast Development</h3>
              <p>Built with Vue 3 Composition API and Laravel for maximum productivity</p>
            </div>
  
            <div class="feature-card">
              <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
              </div>
              <h3>Secure Authentication</h3>
              <p>JWT protected API endpoints and encrypted data storage</p>
            </div>
  
            <div class="feature-card">
              <div class="feature-icon">
                <i class="fas fa-mobile-alt"></i>
              </div>
              <h3>Responsive Design</h3>
              <p>Perfectly adapted for all screen sizes and devices</p>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Stats Section -->
      <section class="stats bg-dark">
        <div class="container">
          <div class="stats-grid">
            <div class="stat-item">
              <div class="stat-number">100%</div>
              <div class="stat-label">Open Source</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">24/7</div>
              <div class="stat-label">Support</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">1K+</div>
              <div class="stat-label">Active Users</div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </template>
  
  <script>
  import { useAuthStore } from '../stores/auth'
  
  export default {
    setup() {
      const auth = useAuthStore()
      return { auth }
    }
  }
  </script>
  
  <style scoped>
  .home-page {
    min-height: 100vh;
  }
  
  /* Hero Section */
  .hero {
    padding: 8rem 0;
    color: white;
    text-align: center;
  }
  
  .bg-gradient {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
  }
  
  .hero-title {
    font-size: 3.5rem;
    margin-bottom: 1.5rem;
    font-weight: 700;
  }
  
  .hero-subtitle {
    font-size: 1.5rem;
    margin-bottom: 2rem;
    opacity: 0.9;
  }
  
  .cta-buttons {
    margin-top: 2rem;
  }
  
  .btn-lg {
    padding: 1rem 2rem;
    font-size: 1.25rem;
    border-radius: 50px;
    transition: all 0.3s ease;
  }
  
  /* Features Section */
  .features {
    padding: 5rem 0;
    background: #f8f9fa;
  }
  
  .section-title {
    text-align: center;
    margin-bottom: 3rem;
    font-size: 2.5rem;
    color: #2c3e50;
  }
  
  .features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
  }
  
  .feature-card {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
  }
  
  .feature-card:hover {
    transform: translateY(-5px);
  }
  
  .feature-icon {
    font-size: 2.5rem;
    color: #3498db;
    margin-bottom: 1rem;
  }
  
  /* Stats Section */
  .stats {
    padding: 4rem 0;
    color: white;
  }
  
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    text-align: center;
  }
  
  .stat-item {
    padding: 1.5rem;
  }
  
  .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
  }
  
  .stat-label {
    font-size: 1.1rem;
    opacity: 0.9;
  }
  
  @media (max-width: 768px) {
    .hero-title {
      font-size: 2.5rem;
    }
    
    .hero-subtitle {
      font-size: 1.2rem;
    }
  }
  </style>