<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded shadow-md space-y-6">
      <h1 class="text-4xl font-semibold text-gray-800 text-center">Регистрация</h1>

      <div class="w-full max-w-sm space-y-4">
        <InputField v-model="name" type="text" placeholder="Имя" />
        <InputField v-model="email" type="email" placeholder="Email" />
        <InputField v-model="password" type="password" placeholder="Пароль" />
        <InputField v-model="passwordConfirmation" type="password" placeholder="Подтверждение пароля" />

        <div class="flex gap-4">
          <PrimaryButton type="button" class="flex-1" :disabled="isLoading" @click="register">
            {{ isLoading ? 'Регистрируем...' : 'Зарегистрироваться' }}
          </PrimaryButton>
          <button
            type="button"
            @click="goToLogin"
            class="flex-1 px-4 py-3 border border-gray-400 rounded-md text-gray-700 hover:bg-gray-200 transition"
          >
            Войти
          </button>
        </div>
      </div>
    </div>

    <!-- Модальное уведомление -->
    <NotificationModal
      v-model="modalVisible"
      :type="modalType"
      :title="modalTitle"
      :message="modalMessage"
      :duration="3000"
    />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/userStore'
import InputField from '@/components/InputField.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import NotificationModal from '@/components/NotificationModal.vue'

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const isLoading = ref(false)

const modalVisible = ref(false)
const modalType = ref<'success' | 'error'>('success')
const modalTitle = ref('')
const modalMessage = ref('')

const router = useRouter()
const userStore = useUserStore()

const register = async () => {
  if (isLoading.value) return
  isLoading.value = true

  try {
    await userStore.register(name.value, email.value, password.value, passwordConfirmation.value)
    // Успех
    modalType.value = 'success'
    modalTitle.value = 'Успех!'
    modalMessage.value = 'Пользователь успешно зарегистрирован.'
    modalVisible.value = true

    // Переход на логин через 1,5 сек
    setTimeout(() => router.push('/login'), 1500)
  } catch (e: any) {
    // Ошибка
    modalType.value = 'error'
    modalTitle.value = 'Ошибка!'
    modalMessage.value = e.response?.data?.message || e.message
    modalVisible.value = true
  } finally {
    isLoading.value = false
  }
}

const goToLogin = () => {
  router.push('/login')
}
</script>
