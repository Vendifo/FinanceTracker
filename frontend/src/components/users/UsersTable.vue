<template>
  <table class="min-w-full bg-white rounded shadow overflow-hidden">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-4 py-2 text-left">ID</th>
        <th class="px-4 py-2 text-left">Имя</th>
        <th class="px-4 py-2 text-left">Фамилия</th>
        <th class="px-4 py-2 text-left">Отчество</th>
        <th class="px-4 py-2 text-left">Email</th>
        <th class="px-4 py-2 text-left">Роль</th>
        <th class="px-4 py-2 text-left">Действия</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users" :key="user.id" class="border-b hover:bg-gray-50">
        <td class="px-4 py-2">{{ user.id }}</td>
        <td class="px-4 py-2">{{ user.first_name || user.name }}</td>
        <td class="px-4 py-2">{{ user.last_name || '-' }}</td>
        <td class="px-4 py-2">{{ user.middle_name || '-' }}</td>
        <td class="px-4 py-2">{{ user.email }}</td>
        <td class="px-4 py-2">{{ user.role?.name || '-' }}</td>
        <td class="px-4 py-2 flex gap-2">
          <button @click="$emit('edit', user)" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">Редактировать</button>
          <button @click="$emit('change-password', user)" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">Сменить пароль</button>
          <button @click="$emit('delete', user)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">Удалить</button>
        </td>
      </tr>
      <tr v-if="loading"><td colspan="7" class="px-4 py-2 text-gray-500">Загрузка...</td></tr>
      <tr v-if="error"><td colspan="7" class="px-4 py-2 text-red-500">{{ error }}</td></tr>
    </tbody>
  </table>
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
