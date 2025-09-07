<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- фон с блюром -->
    <div
      class="absolute inset-0 bg-white/30 backdrop-blur-sm transition-opacity"
      @click="emit('close')"
    ></div>

    <!-- модалка -->
    <div
      class="relative bg-white p-6 rounded-2xl shadow-xl w-96 border z-10"
      @click.stop
    >
      <h2 class="text-2xl font-semibold mb-4 text-gray-800">
        Удалить пользователя?
      </h2>
      <p class="mb-6 text-gray-700">
        Вы действительно хотите удалить
        <strong>{{ user.first_name || user.name }}</strong>?
      </p>

      <div class="flex justify-end gap-3">
        <button
          @click="emit('close')"
          class="px-4 py-2 rounded-lg border bg-gray-100 hover:bg-gray-200 transition"
        >
          Отмена
        </button>
        <button
          @click="emit('confirm', user.id)"
          class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition shadow"
        >
          Удалить
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { User } from '@/api/users'

// Props
const props = defineProps<{ user: User }>()

// Emit с типизацией
const emit = defineEmits<{
  (e: 'close'): void
  (e: 'confirm', userId: number): void
}>()
</script>
