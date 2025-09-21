import axios from 'axios';

// Берем URL из переменной окружения, иначе используем IP VPS
const API_URL = import.meta.env.VITE_API_URL || 'http://147.45.151.90:9000';

const api = axios.create({
  baseURL: `${API_URL}/api`,
  withCredentials: true, // обязательно для Sanctum
});

// Получение CSRF куки
export const getCsrf = async () => {
  try {
    await axios.get(`${API_URL}/sanctum/csrf-cookie`, {
      withCredentials: true,
    });
  } catch (err) {
    console.error('Ошибка получения CSRF:', err);
    throw err;
  }
};

export default api;
