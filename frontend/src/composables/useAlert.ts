import { ref, computed } from 'vue'

const visible = ref(false)
const title = ref('')
const message = ref('')
const type = ref<'success' | 'error' | 'warning' | 'info'>('info')

export function useAlert() {
  const showAlert = (opts: {
    title?: string
    message: string
    type?: 'success' | 'error' | 'warning' | 'info'
  }) => {
    title.value = opts.title || ''
    message.value = opts.message
    type.value = opts.type || 'info'
    visible.value = true
  }

  const closeAlert = () => {
    visible.value = false
  }

  const typeClasses = computed(() => ({
    success: { bg: 'bg-green-50', border: 'border border-green-300', title: 'text-green-600' },
    error: { bg: 'bg-red-50', border: 'border border-red-300', title: 'text-red-600' },
    warning: { bg: 'bg-yellow-50', border: 'border border-yellow-300', title: 'text-yellow-600' },
    info: { bg: 'bg-blue-50', border: 'border border-blue-300', title: 'text-blue-600' },
  }[type.value]))

  return { visible, title, message, typeClasses, showAlert, closeAlert }
}
