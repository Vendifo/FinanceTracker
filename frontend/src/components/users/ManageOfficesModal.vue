<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- Фон с блюром -->
    <div
      class="absolute inset-0 bg-white/30 backdrop-blur-sm transition-opacity"
      @click="emit('close')"
    ></div>

    <!-- Модалка -->
    <div
      class="relative bg-white p-6 rounded-2xl shadow-xl w-96 border z-10"
      @click.stop
    >
      <h2 class="text-2xl font-semibold mb-4 text-gray-800">
        Офисы пользователя {{ user?.name }}
      </h2>

      <div class="space-y-2 max-h-72 overflow-y-auto mb-6">
        <div
          v-for="office in offices"
          :key="office.id"
          class="flex items-center gap-2"
        >
          <input
            type="checkbox"
            :value="office.id"
            v-model="localUserOffices"
            class="w-4 h-4"
          />
          <span>{{ office.name }}</span>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <button
          @click="emit('close')"
          class="px-4 py-2 rounded-lg border bg-gray-100 hover:bg-gray-200 transition"
        >
          Отмена
        </button>
        <button
          @click="saveOffices"
          class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition shadow"
          :disabled="saving"
        >
          {{ saving ? 'Сохранение...' : 'Сохранить' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import type { User } from '@/api/users'
import api from '@/axios'
import { useUserStore } from '@/stores/userStore'
import { useAlert } from '@/composables/useAlert'

interface Office {
  id: number
  name: string
}

const props = defineProps<{
  user: User | null
  offices: Office[]
  modelValue: number[] // v-model
}>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'save', offices: number[]): void
  (e: 'update:modelValue', value: number[]): void
}>()

const localUserOffices = ref<number[]>([...props.modelValue])
const saving = ref(false)

const userStore = useUserStore()
const { showAlert } = useAlert()

// Обновляем локальный массив, если внешний v-model изменился
watch(
  () => props.modelValue,
  (val) => {
    localUserOffices.value = [...val]
  }
)

// Сохранение офисов через PUT
const saveOffices = async () => {
  if (!props.user) return

  saving.value = true
  try {
    const res = await api.put(
      `/users/${props.user.id}/offices`,
      { offices: localUserOffices.value },
      { headers: { Authorization: `Bearer ${userStore.token}` } }
    )

    showAlert({
      type: 'success',
      title: 'Успех',
      message: res.data.message || 'Офисы пользователя обновлены',
    })

    // Обновляем родительский v-model
    emit('update:modelValue', localUserOffices.value)
    emit('save', localUserOffices.value)
    emit('close')
  } catch (err: any) {
    showAlert({
      type: 'error',
      title: 'Ошибка',
      message: err.response?.data?.message || 'Не удалось обновить офисы',
    })
  } finally {
    saving.value = false
  }
}
</script>
