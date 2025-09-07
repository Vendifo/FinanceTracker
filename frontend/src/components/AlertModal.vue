<template>
  <transition name="slide-down">
    <div
      v-if="visible"
      :class="['fixed top-4 right-4 w-80 p-4 rounded shadow flex items-start gap-3', typeClasses.bg, typeClasses.border]"
    >
      <div class="flex-1">
        <h3 :class="['font-bold mb-1', typeClasses.title]">{{ title }}</h3>
        <p class="text-gray-700">{{ message }}</p>
      </div>
      <button @click="closeAlert" class="text-gray-500 hover:text-gray-700 font-bold">&times;</button>
    </div>
  </transition>
</template>

<script setup lang="ts">
import { useAlert } from '@/composables/useAlert'
import { watch } from 'vue'

const { visible, title, message, typeClasses, closeAlert } = useAlert()

// Авто-скрытие через 3 секунды
watch(visible, (val) => {
  if (val) {
    setTimeout(() => {
      closeAlert()
    }, 3000)
  }
})
</script>

<style>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.3s ease;
}
.slide-down-enter-from {
  transform: translateY(-20px);
  opacity: 0;
}
.slide-down-enter-to {
  transform: translateY(0);
  opacity: 1;
}
.slide-down-leave-from {
  transform: translateY(0);
  opacity: 1;
}
.slide-down-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}
</style>
