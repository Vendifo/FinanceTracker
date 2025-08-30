<template>
  <button
    @click="$emit('click')"
    class="px-6 py-3 bg-gray-700 text-white rounded-md hover:bg-gray-800 transition"
  >
    <slot />
  </button>
</template>

<script setup lang="ts"></script>
