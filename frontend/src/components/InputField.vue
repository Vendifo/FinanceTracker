<template>
  <div class="relative w-full">
    <input
      :type="type === 'password' && showPassword ? 'text' : type"
      :placeholder="placeholder"
      :value="modelValue"
      @input="onInput"
      class="w-full p-3 border border-gray-300 rounded-md
             focus:outline-none focus:ring-2 focus:ring-gray-400
             bg-gray-50 text-gray-800 pr-10"
    />
    <button
      v-if="type === 'password'"
      @click="togglePassword"
      type="button"
      class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 transition"
    >
      <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
           viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
      </svg>
      <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
           viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.543-7a9.965 9.965 0 012.14-3.17M6.343 6.343A9.965 9.965 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.157 2.522M3 3l18 18"/>
      </svg>
    </button>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps({
  modelValue: String,
  placeholder: String,
  type: { type: String, default: 'text' }
})

const emit = defineEmits(['update:modelValue'])
const showPassword = ref(false)

const onInput = (event: Event) => {
  const target = event.target as HTMLInputElement | null
  if (target) emit('update:modelValue', target.value)
}

const togglePassword = () => {
  showPassword.value = !showPassword.value
}
</script>
