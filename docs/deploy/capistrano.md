# Деплой с Capistrano

В скелетоне настроен Capistrano 3. Два рецепта: staging и production.

## Установка зависимостей

На машине с которой будет запускаться деплой должен быть установлен Ruby Gems.
Зависимости (гемы) прописаны в Gemfile, это аналог composer.json в руби.

```
gem install
```

## Конфигурация

Рецепты хранятся в папке app/config/deploy.

В рецептах необходимо настроить некоторые моменты.

app/config/staging.rb:

```
server '<host>', user: '<user>', password: '<password>', roles: %w{app db web}
```

Здесь нужно прописать доступы к стейжинг серверу. Доступ паролю самый простой и плохой вариант, лучше использовать ключи - подробнее в доке Capistrano

В app/config/deploy.rb:

```
set :application, 'skeleton' # Название приложения
set :repo_url, 'https://bitbucket.org/prodhub/skeleton-backend' # Репозиторий
set :deploy_to, '/data/sites/skeleton/' # Путь куда будет деплоиться
```

## Настройки сервера

В качестве DOCUMENT_ROOT нужно указать *<путь деплоя>/current/web/*

## Использование

Перед запуском первого деплоя необходимо в *<путь деплоя>/shared/app/config/* создать файл *parameters.yml* с настройками.

Он будет единым для всех релизов. (Тут нужен дискасс, возможно есть идеи получше)


Запуск деплоя на стейжинг:

```
cap staging deploy
```

### Ручной запуск команд

Сгенерить админа с доступами admin;admin

```
cap staging skeleton:create_admin
```

Залить фикстуры

```
cap staging skeleton:fixtures
```
