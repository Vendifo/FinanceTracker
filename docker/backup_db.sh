#!/bin/bash

# ==========================
# Параметры базы данных
# ==========================
DB_CONTAINER="laravel_db"
DB_NAME="laravel"
DB_USER="laravel"
DB_PASS="secret"

# Папка на хосте для бэкапов
BACKUP_DIR="/home/finance_tracker/backups"
DATE=$(date +"%Y-%m-%d_%H-%M")

# Максимальный срок хранения бэкапов (в днях)
RETENTION_DAYS=14

# ==========================
# Создаём папку для бэкапов
# ==========================
mkdir -p $BACKUP_DIR

# ==========================
# Проверяем, запущен ли контейнер
# ==========================
if [ $(docker ps -q -f name=$DB_CONTAINER | wc -l) -eq 0 ]; then
  echo "[$(date)] ERROR: Контейнер $DB_CONTAINER не запущен. Прерываем бэкап."
  exit 1
fi

# ==========================
# Ждём готовности MySQL
# ==========================
echo "[$(date)] Проверяем доступность MySQL..."
until docker exec $DB_CONTAINER mysqladmin ping -u$DB_USER -p$DB_PASS --silent; do
  echo "[$(date)] MySQL ещё не готова. Ждём 5 секунд..."
  sleep 5
done
echo "[$(date)] MySQL готова. Начинаем бэкап."

# ==========================
# Делаем сжатый бэкап
# ==========================
docker exec $DB_CONTAINER /usr/bin/mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/$DB_NAME-$DATE.sql.gz

# Проверяем, создался ли файл
if [ -f "$BACKUP_DIR/$DB_NAME-$DATE.sql.gz" ]; then
  echo "[$(date)] Бэкап успешно создан: $BACKUP_DIR/$DB_NAME-$DATE.sql.gz"
else
  echo "[$(date)] ERROR: Бэкап не создался!"
  exit 1
fi

# ==========================
# Удаляем старые бэкапы
# ==========================
find $BACKUP_DIR -type f -name "*.sql.gz" -mtime +$RETENTION_DAYS -exec rm {} \;
echo "[$(date)] Удалены старые бэкапы старше $RETENTION_DAYS дней."

