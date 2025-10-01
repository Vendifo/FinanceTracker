import axios from 'axios';

// Берем URL из переменной окружения
const API_URL = import.meta.env.VITE_API_URL || 'https://касса-крым.рф';

const api = axios.create({
  baseURL: `${API_URL}/api`,
  withCredentials: true, // важно для cookie-based auth
});

// Получение CSRF cookie (обязательно перед login/register)
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
