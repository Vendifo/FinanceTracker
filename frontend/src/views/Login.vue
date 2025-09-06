<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded shadow-md space-y-6">
      <h1 class="text-4xl font-semibold text-gray-800 text-center">Вход</h1>

      <div class="space-y-4">
        <InputField v-model="form.email" type="email" placeholder="Email" />
        <InputField v-model="form.password" type="password" placeholder="Пароль" />

        <div class="flex gap-4">
          <PrimaryButton :disabled="isLoading" @click="login">
            {{ isLoading ? 'Входим...' : 'Войти' }}
          </PrimaryButton>
          <button @click="goToRegister" class="flex-1 px-4 py-3 border rounded text-gray-700 hover:bg-gray-200 transition">
            Зарегистрироваться
          </button>
        </div>
      </div>
    </div>

    <NotificationModal v-model="modal.visible" :type="modal.type" :title="modal.title" :message="modal.message" :duration="3000"/>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/userStore'
import InputField from '@/components/InputField.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import NotificationModal from '@/components/NotificationModal.vue'

// форма
const form = reactive({ email: '', password: '' })
const isLoading = ref(false)

// уведомления
const modal = reactive({
  visible: false,
  type: 'error' as 'error' | 'success',
  title: '',
  message: '',
})

const router = useRouter()
const userStore = useUserStore()

const login = async () => {
  if (isLoading.value) return
  isLoading.value = true
  try {
    await userStore.login(form.email, form.password)
    router.push('/dashboard')
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

const goToRegister = () => router.push('/register')
</script>
