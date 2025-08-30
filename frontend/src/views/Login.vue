<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white p-8 rounded shadow-md space-y-6">
      <h1 class="text-4xl font-semibold text-gray-800 text-center">Вход</h1>

      <div class="w-full max-w-sm space-y-4">
        <InputField v-model="email" type="email" placeholder="Email" />
        <InputField v-model="password" type="password" placeholder="Пароль" />

        <div class="flex gap-4">
          <PrimaryButton class="flex-1" @click="login">Войти</PrimaryButton>
          <button
            @click="goToRegister"
            class="flex-1 px-4 py-3 border border-gray-400 rounded-md text-gray-700 hover:bg-gray-200 transition"
          >
            Зарегистрироваться
          </button>
        </div>
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

const email = ref('')
const password = ref('')
const router = useRouter()
const userStore = useUserStore()

const login = async () => {
  try {
    await userStore.login(email.value, password.value)
    router.push('/dashboard')
  } catch (e: any) {
    alert(e.response?.data?.message || e.message)
  }
}

const goToRegister = () => {
  router.push('/register')
}
</script>
