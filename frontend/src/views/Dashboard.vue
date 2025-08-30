<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50">
    <h1 class="text-3xl font-bold mb-4">Добро пожаловать!</h1>
    <p class="text-gray-600">Это заглушка главной страницы.</p>
    <router-link
      to="/login"
      class="mt-6 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
    >
      Выйти
    </router-link>
  </div>
</template>

<script setup lang="ts">
</script>
