import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:9000/api',
  withCredentials: true, // обязательно для Sanctum
});

// Получение CSRF куки
export const getCsrf = async () => {
  try {
    await axios.get(`${import.meta.env.VITE_API_URL || 'http://localhost:9000'}/sanctum/csrf-cookie`, {
      withCredentials: true,
    });
  } catch (err) {
    console.error('Ошибка получения CSRF:', err);
    throw err;
  }
};

export default api;
