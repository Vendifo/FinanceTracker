<template>
  <transition name="slide-fade">
    <div
      v-if="visible"
      class="fixed inset-x-0 top-4 flex justify-center z-50 pointer-events-none"
    >
      <div
        :class="[
          'max-w-md w-full px-6 py-4 rounded-lg shadow-md pointer-events-auto border',
          type === 'success'
            ? 'bg-green-100 text-green-800 border-green-200'
            : 'bg-red-100 text-red-800 border-red-200'
        ]"
      >
        <div class="flex justify-between items-start">
          <h3 class="text-lg font-semibold">{{ title }}</h3>
          <button
            @click="close"
            class="ml-4 font-bold text-xl leading-none hover:text-gray-700"
          >&times;</button>
        </div>
        <p class="mt-2 text-sm">{{ message }}</p>
      </div>
    </div>
  </transition>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

interface Props {
  type?: 'success' | 'error'
  title?: string
  message: string
  duration?: number
  modelValue: boolean
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue'])

const visible = ref(props.modelValue)

watch(() => props.modelValue, val => {
  visible.value = val
  if (val && props.duration) {
    setTimeout(() => close(), props.duration)
  }
})

const close = () => {
  visible.value = false
  emit('update:modelValue', false)
}
</script>

<style>
/* Плавная анимация появления сверху */
.slide-fade-enter-active {
  transition: all 0.3s ease;
}
.slide-fade-leave-active {
  transition: all 0.3s ease;
}
.slide-fade-enter-from {
  opacity: 0;
  transform: translateY(-20px);
}
.slide-fade-enter-to {
  opacity: 1;
  transform: translateY(0);
}
.slide-fade-leave-from {
  opacity: 1;
  transform: translateY(0);
}
.slide-fade-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}
</style>
