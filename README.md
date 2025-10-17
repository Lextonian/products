# Products

## Описание
Тестовый проект с продуктами и категориями на базе Laravel 12 и Postgres 16. Для запуска необходимо установить Docker.

## Установка и развертывание

### 1. Клонирование репозитория
Клонируйте репозиторий на ваш сервер или локальную машину:
```bash
git clone https://github.com/Lextonian/products.git
cd products
```

### 2. Настройка окружения
Скопируйте файл `.env.example` в `.env`:
```bash
cp .env.example .env
```

Отредактируйте файл `.env`, добавив следующие обязательные параметры:
```
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```
- Параметры базы данных (`DB_*`) настройте в соответствии с вашей конфигурацией.

### 3. Установка зависимостей и запуск
Установите зависимости PHP с помощью Composer через Docker:
```bash
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install
```

```bash
./vendor/bin/sail up
```

### 3.1 Добавления алиаса

Для удобства можно добавить алиас
```bash
echo "alias sail='./vendor/bin/sail'" >> ~/.bashrc && source ~/.bashrc
```
В таком случае команда сокращается до `sail`. Например, `sail up`


### 4. Генерация ключа приложения
Сгенерируйте ключ приложения Laravel:
```bash
./vendor/bin/sail artisan key:generate
```

### 5. Настройка базы данных
```bash
./vendor/bin/sail artisan migrate
```

Для быстрого запуска рекомендуется запустить:
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

## Коллекция Postman
Для тестирования рекомендуется использоваться Postman и импортировать коллекцию. Файл:
`
productsPostman.json
`
в корневом каталоге репозитория. Там предоставлены все маршруты приложения

В переменных коллекция при необходимости можно поменять `baseURL`. По
уполчанию указан `http://localhost`. Также для работы с админскими роутами необходимо в коллекции запустить запрос `login` (данные уже подставлены из сидера) или зарегистироваться нового пользователя `register`. Токен `auth_token` будет подставлен автоматически.


## Безопасность
Используется Laravel Sanctum для админских маршрутов. Для упрощения не была введена роль "Админ". Для авторизации необходимо сначала отправить запрос по {{baseURL}}/api/auth/register и в последствии {{baseURL}}/api/auth/login. Далее необходимо вставить токен в заголовок Authorization: Bearer {{token}} перед отправкой запроса.

## API Маршруты

### Аутентификация

| Метод | Эндпоинт | Действие |
|-------|----------|----------|
| `POST` | `api/auth/login` | `login` |
| `POST` | `api/auth/logout` | `logout` |
| `POST` | `api/auth/register` | `register` |

### Публичные маршруты

| Метод | Эндпоинт | Действие |
|-------|----------|----------|
| `GET` | `api/public/product_categories` | `categoriesTree` |
| `GET` | `api/public/product_categories_with_products` | `categoriesWithProducts` |
| `GET` | `api/public/products` | `products` |
| `GET` | `api/public/products/{product}` | `product` |

### Админка: Категории товаров

| Метод | Эндпоинт | Действие |
|-------|----------|----------|
| `GET/HEAD` | `api/admin/product_categories` | `index` |
| `POST` | `api/admin/product_categories` | `store` |
| `GET/HEAD` | `api/admin/product_categories/{product_category}` | `show` |
| `PUT/PATCH` | `api/admin/product_categories/{product_category}` | `update` |
| `DELETE` | `api/admin/product_categories/{product_category}` | `destroy` |

### Админка: Товары

| Метод | Эндпоинт | Действие |
|-------|----------|----------|
| `GET/HEAD` | `api/admin/products` | `index` |
| `POST` | `api/admin/products` | `store` |
| `GET/HEAD` | `api/admin/products/{product}` | `show` |
| `PUT/PATCH` | `api/admin/products/{product}` | `update` |
| `DELETE` | `api/admin/products/{product}` | `destroy` |
