# Crematori

Проект представляет собой SPA с фронтендом на Vue 3 и бэкендом на Laravel 12, полностью разделённый на backend и frontend. Backend предоставляет RESTful API с аутентификацией через Laravel Sanctum.

---

## 🚀 Стек технологий

**Бэкенд:**
- PHP 8.2+
- Laravel 12
- MySQL
- Laravel Sanctum (SPA аутентификация)
- Eloquent ORM
- Artisan CLI

**Фронтенд:**
- Vue 3
- Vue Router
- Pinia
- Axios
- Vite
- Tailwind CSS

**Инструменты и окружение:**
- Docker + Docker Compose
- WSL2 (для Windows)
- phpMyAdmin (для управления БД)
- Git

---

## 📦 Установка и запуск

1. Клонируем репозиторий:
```
git clone https://github.com/<your-username>/Crematori.git
cd Crematori
```
Поднимаем Docker-контейнеры:
```
docker-compose up -d --build
```
Устанавливаем зависимости Laravel:
```
docker exec -it laravel_app composer install
docker exec -it laravel_app php artisan migrate
docker exec -it laravel_app php artisan db:seed
```
Устанавливаем зависимости фронтенда:
```
docker exec -it vue_frontend npm install
docker exec -it vue_frontend npm run dev
```
Проект доступен по адресу:

Backend API: http://localhost:9000/api
Frontend: http://localhost:5173

