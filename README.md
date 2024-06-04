# Лента постов

![Главная страница](https://downloader.disk.yandex.ru/preview/8a9dd4c41bf51736837481147a71eead3bf09b0b3b824393d3d840e7114158b7/665f32d7/z8l9htbM7VuTja3w9iHvvQ9371i6Hbz56_a68_7DAVDx5nMDiBgqhZQmc4lm24R9bXDEmpOF7v5WaqJEzoFohA%3D%3D?uid=0&filename=chrome_ug5AZiTzfk.png&disposition=inline&hash=&limit=0&content_type=image%2Fpng&owner_uid=0&tknv=v2&size=2048x2048)

### Что реализовано
- [x] Основной функционал
- [x] Смена языка (русский, английский)
- [x] Смена темы (светлая, темная)
- [x] Тесты для основного функционала
- [x] Логирование основных действий в бд

### Для локального запуска

1. Создать директорию для докера

```
mkdir ./storage/docker
```

2. Скопировать .env.example

```
cp .env.example .env
```

3. Добавить пользователя в .env

```
echo UID=$(id -u) >> .env
echo GID=$(id -g) >> .env
```

4. Запустить сервисы докера

```
docker compose up -d --build
```

5. Установить зависимости

```
docker exec posts-app composer install
```

6. Опубликовать ключ

```
docker exec posts-app php artisan key:generate
```

6. Сделать миграции

```
docker exec posts-app php artisan migrate --seed
```
