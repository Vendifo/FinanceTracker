import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import path from 'path'

export default defineConfig({
  plugins: [vue(), tailwindcss()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },
  server: {
    host: true,
    proxy: {
      '/api': {
        target: 'http://92.255.76.182:9000',
        changeOrigin: true,
        secure: false,
      },
      '/sanctum/csrf-cookie': {
        target: 'http://92.255.76.182:9000',
        changeOrigin: true,
        secure: false,
      },
    },
  },
})
