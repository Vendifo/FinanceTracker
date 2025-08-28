import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    host: true, // чтобы Docker мог подключаться
    proxy: {
      '/api': {
        target: 'http://host.docker.internal:9000',
        changeOrigin: true,
        secure: false,
      },
    },
  },
})
