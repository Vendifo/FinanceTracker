<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded shadow-md space-y-6">
      <h1 class="text-4xl font-semibold text-gray-800 text-center">Регистрация</h1>

      <div class="space-y-4">
        <InputField v-model="form.name" type="text" placeholder="Имя" />
        <InputField v-model="form.email" type="email" placeholder="Email" />
        <InputField v-model="form.password" type="password" placeholder="Пароль" />
        <InputField v-model="form.passwordConfirmation" type="password" placeholder="Подтверждение пароля" />

        <div class="flex gap-4">
          <PrimaryButton :disabled="isLoading" @click="register">
            {{ isLoading ? 'Регистрируем...' : 'Зарегистрироваться' }}
          </PrimaryButton>
          <button @click="goToLogin" class="flex-1 px-4 py-3 border rounded text-gray-700 hover:bg-gray-200 transition">
            Войти
          </button>
        </div>
      </div>
    </div>

    <NotificationModal
      v-model="modal.visible"
      :type="modal.type"
      :title="modal.title"
      :message="modal.message"
      :duration="3000"
    />
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/userStore'
import InputField from '@/components/InputField.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import NotificationModal from '@/components/NotificationModal.vue'

// форма регистрации
const form = reactive({
  name: '',
  email: '',
  password: '',
  passwordConfirmation: '',
})

const isLoading = ref(false)

// модальные уведомления
const modal = reactive({
  visible: false,
  type: 'success' as 'success' | 'error',
  title: '',
  message: '',
})

const router = useRouter()
const userStore = useUserStore()

const register = async () => {
  if (isLoading.value) return
  isLoading.value = true

  try {
    await userStore.register(
      form.name,
      form.email,
      form.password,
      form.passwordConfirmation
    )

    modal.type = 'success'
    modal.title = 'Успех!'
    modal.message = 'Пользователь успешно зарегистрирован.'
    modal.visible = true

    setTimeout(() => router.push('/login'), 1500)
  } catch (err: unknown) {
    modal.type = 'error'
    modal.title = 'Ошибка!'
    if ((err as any)?.response?.data?.message) {
      modal.message = (err as any).response.data.message
    } else {
      modal.message = (err as Error).message
    }
    modal.visible = true
  } finally {
    isLoading.value = false
  }
}

const goToLogin = () => router.push('/login')
</script>
