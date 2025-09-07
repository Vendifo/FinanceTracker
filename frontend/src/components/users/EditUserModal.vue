<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- затемнение -->
    <div
      class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"
      @click="emit('close')"
    ></div>

    <!-- модалка -->
    <div
      class="relative bg-white p-6 rounded-2xl shadow-xl w-96 max-h-[90vh] overflow-auto border z-10"
    >
      <h2 class="text-2xl font-semibold mb-6 text-gray-800">
        Редактировать пользователя
      </h2>

      <div class="flex flex-col gap-4 mb-6">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1"
            >Никнейм</label
          >
          <input
            v-model="localUser.name"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Имя</label>
          <input
            v-model="localUser.first_name"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1"
            >Фамилия</label
          >
          <input
            v-model="localUser.last_name"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1"
            >Отчество</label
          >
          <input
            v-model="localUser.middle_name"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1"
            >Email</label
          >
          <input
            v-model="localUser.email"
            type="email"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1"
            >Пароль</label
          >
          <div class="relative">
            <input
              :type="showPassword ? 'text' : 'password'"
              v-model="newPassword"
              autocomplete="new-password"
              class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition pr-10"
            />
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-gray-700"
            >
              <!-- иконки -->
              <svg
                v-if="!showPassword"
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477
                  0 8.268 2.943 9.542 7-1.274 4.057-5.065
                  7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                />
              </svg>
              <svg
                v-else
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477
                  0-8.268-2.943-9.542-7a9.969 9.969 0
                  012.178-3.568M6.2 6.2A9.969 9.969 0
                  0012 5c4.477 0 8.268 2.943 9.542
                  7a9.953 9.953 0 01-4.043 5.197M15
                  12a3 3 0 01-4.243 2.829M9.88
                  9.88A3 3 0 0115 12m-6.708
                  6.708L20.485 6.515M3.515
                  3.515l4.243 4.243"
                />
              </svg>
            </button>
          </div>
          <p class="text-xs text-gray-500 mt-1">
            Оставьте пустым, если менять пароль не нужно.
          </p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Роль</label>
          <select
            v-model.number="selectedRoleId"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
          >
            <option :value="undefined" disabled>Выберите роль</option>
            <option
              v-for="role in roles"
              :key="role.id"
              :value="role.id"
            >
              {{ role.name }}
            </option>
          </select>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <button
          type="button"
          @click="emit('close')"
          class="px-4 py-2 rounded-lg border bg-gray-100 hover:bg-gray-200 transition"
        >
          Отмена
        </button>
        <button
          type="button"
          @click="save"
          class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition shadow"
        >
          Сохранить
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from 'vue'
import type { User, Role } from '@/api/users'
import { useAlert } from '@/composables/useAlert'

const { showAlert } = useAlert()

interface Props {
  user: User | null
  roles: Role[]
}

const props = defineProps<Props>()
const emit = defineEmits<{
  (e: 'close'): void
  (e: 'save', user: User): void
  (e: 'savePassword', payload: { id: number; password: string }): void
}>()

const localUser = reactive<User>({
  id: 0,
  name: '',
  first_name: '',
  last_name: '',
  middle_name: '',
  email: '',
  role: undefined
})

const selectedRoleId = ref<number | undefined>(undefined)
const newPassword = ref<string>('')
const showPassword = ref(false)

watch(
  () => props.user,
  (newUser) => {
    if (newUser) {
      localUser.id = newUser.id
      localUser.name = newUser.name
      localUser.email = newUser.email
      localUser.first_name = newUser.first_name || ''
      localUser.last_name = newUser.last_name || ''
      localUser.middle_name = newUser.middle_name || ''
      selectedRoleId.value = newUser.role?.id
      newPassword.value = ''
    }
  },
  { immediate: true }
)

const save = () => {
  if (!selectedRoleId.value) {
    showAlert({ type: 'error', title: 'Ошибка', message: 'Выберите роль' })
    return
  }

  localUser.name = [localUser.first_name, localUser.last_name, localUser.middle_name]
    .filter(Boolean)
    .join(' ')

  localUser.role = props.roles.find((r) => r.id === selectedRoleId.value)

  emit('save', localUser)

  const pwd = newPassword.value.trim()
  if (pwd) {
    emit('savePassword', { id: localUser.id, password: pwd })
    newPassword.value = ''
    showAlert({ type: 'success', title: 'Успех', message: 'Данные и пароль обновлены' })
  } else {
    showAlert({ type: 'success', title: 'Успех', message: 'Данные пользователя обновлены' })
  }
}
</script>
