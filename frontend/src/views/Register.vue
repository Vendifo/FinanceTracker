<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded shadow-md space-y-6">
      <h1 class="text-4xl font-semibold text-gray-800 text-center">Регистрация</h1>

      <InputField v-model="name" type="text" placeholder="Имя" />
      <InputField v-model="email" type="email" placeholder="Email" />
      <InputField v-model="password" type="password" placeholder="Пароль" />
      <InputField v-model="passwordConfirmation" type="password" placeholder="Подтверждение пароля" />

      <div class="flex gap-4">
        <PrimaryButton class="flex-1" @click="register">Зарегистрироваться</PrimaryButton>
        <button @click="goToLogin"
          class="flex-1 px-4 py-3 border border-gray-400 rounded-md text-gray-700 hover:bg-gray-200 transition">
          Войти
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '@/stores/userStore'
import InputField from '@/components/InputField.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')

const router = useRouter()
const userStore = useUserStore()

const register = async () => {
  try {
    await userStore.register(name.value, email.value, password.value, passwordConfirmation.value)
    alert('Пользователь создан')
    router.push('/login')
  } catch (e: any) {
    alert(e.response?.data?.message || e.message)
  }
}

const goToLogin = () => {
  router.push('/login')
}
</script>
