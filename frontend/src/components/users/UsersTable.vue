<template>
  <div class="bg-white text-gray-800 rounded-lg shadow p-6">
    <h3 class="text-2xl font-semibold mb-4">Пользователи</h3>

    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50 text-gray-600 text-sm font-medium">
          <tr>
            <th class="px-4 py-2 text-left">Никнейм</th>
            <th class="px-4 py-2 text-left">Имя</th>
            <th class="px-4 py-2 text-left">Фамилия</th>
            <th class="px-4 py-2 text-left">Отчество</th>
            <th class="px-4 py-2 text-left">Email</th>
            <th class="px-4 py-2 text-left">Роль</th>
            <th class="px-4 py-2 text-left">Действия</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="user in users"
            :key="user.id"
            class="hover:bg-gray-50 transition"
          >
            <td class="px-4 py-3 font-medium text-gray-900">{{ user.name }}</td>
            <td class="px-4 py-3 text-gray-700">{{ user.first_name }}</td>
            <td class="px-4 py-3 text-gray-700">{{ user.last_name || '-' }}</td>
            <td class="px-4 py-3 text-gray-700">{{ user.middle_name || '-' }}</td>
            <td class="px-4 py-3 text-gray-700">{{ user.email }}</td>
            <td class="px-4 py-3 text-gray-600">{{ user.role?.name || '-' }}</td>
            <td class="px-4 py-3 flex gap-2">
              <button
                @click="$emit('edit', user)"
                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition"
              >
                Редактировать
              </button>
              <button
                @click="$emit('change-password', user)"
                class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded transition"
              >
                Сменить пароль
              </button>
              <button
                @click="$emit('delete', user)"
                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition"
              >
                Удалить
              </button>
            </td>
          </tr>

          <tr v-if="loading">
            <td colspan="7" class="px-4 py-3 text-gray-500 text-center">
              Загрузка...
            </td>
          </tr>

          <tr v-if="error">
            <td colspan="7" class="px-4 py-3 text-red-500 text-center">
              {{ error }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { User, Role } from '@/api/users'

interface Props {
  users: User[]
  roles: Role[]
  loading: boolean
  error: string | null
}

const props = defineProps<Props>()
const emits = defineEmits<{
  (e: 'edit', user: User): void
  (e: 'delete', user: User): void
  (e: 'change-password', user: User): void
}>()
</script>
