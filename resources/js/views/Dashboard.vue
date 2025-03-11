<template>
    <div class="home">
      <h1>Trang Chủ Tạm Thời</h1>
      <p>Chào mừng bạn đến với trang quản lý bài viết!</p>
  
      <!-- Phần giới thiệu -->
      <section class="intro">
        <h2>Giới thiệu</h2>
        <p>
          Đây là trang chủ tạm thời của ứng dụng quản lý bài viết. Bạn có thể sử dụng các tính năng sau:
        </p>
        <ul>
          <li>Tạo bài viết mới.</li>
          <li>Xem danh sách bài viết.</li>
          <li>Chỉnh sửa hoặc xóa bài viết.</li>
        </ul>
      </section>
  
      <!-- Thông tin liên hệ -->
      <section class="contact">
        <h2>Liên hệ</h2>
        <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ:</p>
        <ul>
          <li>Email: support@example.com</li>
          <li>Điện thoại: 0123 456 789</li>
        </ul>
      </section>
    </div>
  </template>
  
  <script setup>
  // Không cần import component nào vì trang này là tạm thời và fix cứng
  </script>
  
  <style scoped>
  .home {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
  }
  
  h1 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 20px;
  }
  
  p {
    font-size: 1.1rem;
    color: #555;
    line-height: 1.6;
  }
  
  .intro,
  .contact {
    margin-top: 30px;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  h2 {
    font-size: 1.8rem;
    color: #444;
    margin-bottom: 15px;
  }
  
  ul {
    list-style-type: none;
    padding: 0;
  }
  
  ul li {
    font-size: 1rem;
    color: #666;
    margin: 10px 0;
  }
  </style>