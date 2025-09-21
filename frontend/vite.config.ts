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
    proxy: {
      '/api': {
        target: 'http://147.45.151.90:9000',
        changeOrigin: true,
        secure: false,
        cookieDomainRewrite: '',
      },
      '/sanctum': {
        target: 'http://147.45.151.90:9000',
        changeOrigin: true,
        secure: false,
        cookieDomainRewrite: '',
      },
    },
  },
})
