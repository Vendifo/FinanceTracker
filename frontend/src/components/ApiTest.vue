<template>
  <div>
    <h1>API Response:</h1>
    <p>{{ message }}</p>
    <button @click="callApi">Call API</button>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'

const message = ref<string>('')

const callApi = async () => {
  try {
    const res = await axios.get('/api/hello')
    message.value = res.data.message
  } catch (e: any) {
    message.value = 'Error: ' + e.message
  }
}
</script>
