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
    port: 5173,
    strictPort: true,
    cors: true,
    allowedHosts: ['vue_frontend', 'localhost', 'касса-крым.рф', 'xn----7sba2bdm1aea8h.xn--p1ai'],
    proxy: {
      '/api': {
        target: 'https://касса-крым.рф',
        changeOrigin: true,
        secure: false,
      },
      '/sanctum': {
        target: 'https://касса-крым.рф',
        changeOrigin: true,
        secure: false,
      },
    },
    hmr: {
      timeout: 24 * 60 * 60 * 1000, // 24 часа в миллисекундах
    },
  },
})
