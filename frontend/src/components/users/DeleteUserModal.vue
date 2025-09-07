<template>
  <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded shadow w-80">
      <h2 class="text-lg font-bold mb-4">Удалить пользователя?</h2>
      <p class="mb-4">
        Вы действительно хотите удалить <strong>{{ user.first_name || user.name }}</strong>?
      </p>

      <div class="flex justify-end gap-2">
        <button
          @click="emit('close')"
          class="px-3 py-1 rounded border"
        >
          Отмена
        </button>
        <button
          @click="emit('confirm', user.id)"
          class="px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600"
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
