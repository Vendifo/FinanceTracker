<template>
  <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded shadow w-96 max-h-[90vh] overflow-auto">
      <h2 class="text-xl font-bold mb-4">Редактировать пользователя</h2>

      <div class="flex flex-col gap-2 mb-4">
        <label>Имя</label>
        <input v-model="localUser.first_name" class="border px-2 py-1 rounded" />

        <label>Фамилия</label>
        <input v-model="localUser.last_name" class="border px-2 py-1 rounded" />

        <label>Отчество</label>
        <input v-model="localUser.middle_name" class="border px-2 py-1 rounded" />

        <label>Email</label>
        <input v-model="localUser.email" class="border px-2 py-1 rounded" />

        <label>Роль</label>
        <select v-model.number="selectedRoleId" class="border px-2 py-1 rounded">
          <option :value="undefined" disabled>Выберите роль</option>
          <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
        </select>
      </div>

      <div class="flex justify-end gap-2">
        <button @click="emit('close')" class="px-3 py-1 rounded border">Отмена</button>
        <button @click="save" class="px-3 py-1 rounded bg-green-500 text-white hover:bg-green-600">Сохранить</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from 'vue'
import type { User, Role } from '@/api/users'

interface Props {
  user: User
  roles: Role[]
}

const props = defineProps<Props>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'save', user: User, roleId?: number): void
}>()

// Локальная копия пользователя для редактирования
const localUser = reactive({ ...props.user })

// Выбранная роль (number | undefined)
const selectedRoleId = ref<number | undefined>(props.user.role?.id)

// Следим за изменением props.user
watch(
  () => props.user,
  (newUser) => {
    Object.assign(localUser, newUser)
    selectedRoleId.value = newUser.role?.id
  }
)

// Сохраняем изменения
const save = () => {
  emit('save', localUser, selectedRoleId.value)
}
</script>
