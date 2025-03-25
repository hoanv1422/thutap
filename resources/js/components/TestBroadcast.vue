<template>
    <div>
      <h1>Test Broadcast</h1>
      <p v-for="(msg, index) in messages" :key="index">
        {{ msg }}
      </p>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import echo from '../echo' // Import file echo.js
  
  const messages = ref([])
  
  onMounted(() => {
    // 1) Nếu KHÔNG dùng broadcastAs() trong event, mặc định event name = "App\Events\TestEvent"
    // => lắng nghe với listen('.App\\Events\\TestEvent', ...)
    echo.channel('test-channel')
      .listen('.App\\Events\\TestEvent', (e) => {
        console.log('Nhận sự kiện TestEvent:', e)
        messages.value.push(e.message)
      })
  
    // 2) Nếu CÓ dùng broadcastAs() = 'TestEvent', thì thay bằng:
    // echo.channel('test-channel')
    //   .listen('.TestEvent', (e) => {
    //     console.log('Nhận sự kiện TestEvent:', e)
    //     messages.value.push(e.message)
    //   })
  })
  </script>
  