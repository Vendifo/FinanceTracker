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
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/userStore'
import InputField from '@/components/InputField.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import { useAlert } from '@/composables/useAlert'

const { showAlert } = useAlert()
// форма регистрации
const form = reactive({
  name: '',
  email: '',
  password: '',
  passwordConfirmation: '',
})

const isLoading = ref(false)
const router = useRouter()
const userStore = useUserStore()

// универсальная функция регистрации
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

    showAlert({
      type: 'success',
      title: 'Успех!',
      message: 'Пользователь успешно зарегистрирован.'
    })

    // перенаправление через 1.5 секунды
    setTimeout(() => router.push('/login'), 1500)
  } catch (err: unknown) {
    showAlert({
      type: 'error',
      title: 'Ошибка!',
      message: (err as any)?.response?.data?.message || (err as Error).message || 'Произошла ошибка'
    })
  } finally {
    isLoading.value = false
  }
}

const goToLogin = () => router.push('/login')
</script>
