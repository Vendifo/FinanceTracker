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
    host: true, // слушаем все интерфейсы
    port: 5173,
    strictPort: false,
    hmr: {
      host: 'касса-крым.рф', // для HMR
    },
    cors: true,
    allowedHosts: ['.'], // разрешить все хосты
    proxy: {
      '/api': {
        target: 'http://laravel_nginx:80',
        changeOrigin: true,
        secure: false,
        cookieDomainRewrite: '',
      },
      '/sanctum': {
        target: 'http://laravel_nginx:80',
        changeOrigin: true,
        secure: false,
        cookieDomainRewrite: '',
      },
    },
  },
})
