# Laravel Google Sheets CRUD Integration

Проект представляет собой CRUD-приложение с интеграцией Google Sheets, где данные автоматически синхронизируются между базой данных и Google-таблицей.

## Основные функции

    Полноценный CRUD для модели Item

    Поле status с enum-значениями (Allowed/Prohibited)

    Генерация 1000 тестовых записей

    Очистка всей таблицы

    Интеграция с Google Sheets (автосинхронизация раз в минуту)

    Консольная команда для вывода данных из таблицы

    REST API endpoint для получения данных

## Установка

Клонируйте репозиторий:

    git clone https://github.com/shamalli/laravel-google-sheets.git
    cd laravel-google-sheets

Установите зависимости:

    composer install

Выполните миграции:

    php artisan migrate

Установите необходимые пакеты:

    composer require google/apiclient spatie/laravel-settings
    php artisan vendor:publish --provider="Spatie\Settings\SettingsServiceProvider" --tag="migrations"
    php artisan migrate

## Настройка Google Sheets API

## Создайте сервисный аккаунт в Google Cloud Console

    1. Перейдите в Google Cloud Console https://console.cloud.google.com/

    2. Создайте новый проект или выберите существующий

    3. В меню навигации выберите "APIs & Services" → "Credentials"

    4. Нажмите "Create Credentials" → "Service account"

    5. Заполните:

        Service account name (например, "Laravel Google Sheets")

        Service account ID (автозаполнение)

        Описание (опционально)

    6. Нажмите "Create and Continue"

    7. На шаге "Grant this service account access to project" добавьте роль:

        Project → Editor (или более ограниченную, если знаете какие именно права нужны)

    8. Нажмите "Continue" → "Done"

## Скачайте JSON-ключ и сохраните как storage/app/credentials.json

    1. В списке сервисных аккаунтов найдите созданный

    2. Вкладка "Keys" → "Add Key" → "Create new key"

    3. Выберите тип JSON

    4. Нажмите "Create" - файл автоматически скачается

## Настройки доступа

    1. Откройте свою Google-таблицу

    2. Нажмите "Настройки доступа" в правом верхнем углу

    3. Выберите "Все у кого есть ссылка"

    4. Выберите права доступа: "Редактор"

    5. Нажмите "Готово"

## Запуск

Запустите сервер разработки:

    php artisan serve

Для автоматической синхронизации добавьте в cron:

    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

Или запустите вручную:

    php artisan schedule:work

## Тестирование

Тестовый пользователь создается автоматически

    логин: admin@example.com
    пароль: admin123

## Команды

Получение данных из таблицы:

    php artisan google-sheet:fetch

С ограничением количества записей:

    php artisan google-sheet:fetch --count=20

## API Endpoints

    GET /items - список Items

    GET /items/create - форма для создания item

    POST /items/store - создать item

    GET /items/edit - форма для редакторования item

    POST /items/update - редакторовать item

    POST /items/destroy - удалить item

    POST /items/generate - сгенерировать 1000 items

    POST /items/set-google-sheet - задать URL на документ google sheet

    GET /fetch - получить данные из таблицы google sheet

    GET /fetch/{count} - получить ограниченное количество записей