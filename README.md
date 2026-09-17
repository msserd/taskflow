# TaskFlow

Менеджер задач с REST API, ролевой моделью и асинхронной обработкой уведомлений.

## Стек

- **Бэкенд:** Laravel 13, PHP 8.3
- **База данных:** PostgreSQL
- **Кэш и очереди:** Redis
- **Фронтенд:** Vue 3 + Inertia.js, Tailwind CSS
- **Аутентификация:** Laravel Breeze + Sanctum
- **Инфраструктура:** Docker (Laravel Sail)
- **Документация API:** Scramble (OpenAPI)
- **Тесты:** PHPUnit

## Возможности

- Аутентификация (Laravel Breeze + Sanctum)
- CRUD задач
- Разграничение доступа (пользователь / админ)
- Фильтрация по статусу и диапазону дедлайна
- Сортировка и пагинация
- Кэширование (Redis)
- Очереди: уведомления по email + логирование изменений (Redis)
- REST API с документацией (Scramble)
- Feature и Unit тесты

## Требования

- Docker
- Docker Compose
- Composer (для установки зависимостей)
- Node.js (для сборки фронтенда)

## Установка

### 1. Создайте алиас для Sail (опционально, но удобно)

```bash
echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >> ~/.bashrc
source ~/.bashrc
```

>Если вы не хотите создавать алиас, замените <code>sail</code> на <code>./vendor/bin/sail</code> во всех командах ниже.

### 2. Клонируйте репозиторий и установите зависимости

```bash
git clone https://github.com/msserd/taskflow.git
cd taskflow
cp .env.example .env
composer install
sail up -d
sail artisan key:generate
sail artisan migrate --seed
sail npm install
sail npm run build
```

## Запуск

```bash
sail up -d
sail npm run dev
```

Открыть http://localhost в браузере

### Данные для входа

| Роль | Email | Пароль |
|-----|--------|--------|
| Админ | admin@test.com | password |
| Пользователь | user@test.com | password |

### Очередь

Для обработки уведомлений запусти воркер в отдельном терминале:

```bash
sail artisan queue:work
```

### Почта

Письма перехватываются Mailpit. Веб-интерфейс: http://localhost:8025

### Документация API

Интерактивная документация: http://localhost/docs/api

## Тесты

```bash
sail artisan test
```
