[![SensioLabsInsight](https://insight.sensiolabs.com/projects/8c6282f5-ea51-4cd4-a2dc-1370cc0725e6/big.png)](https://insight.sensiolabs.com/projects/8c6282f5-ea51-4cd4-a2dc-1370cc0725e6)

# Что такое? #
База для новых проектов. Symfony 2.8.
***
# Что там есть? #

### [Vagrant](https://www.vagrantup.com/) ###
Используй версию vagrant >= 1.8.*

Обязательно установи плагин:

vagrant plugin install vagrant-vbguest

### [Common Bundle](https://bitbucket.org/prodhub/common-bundle/overview)
Общие вещи для наших решений.

### [Sonata](https://sonata-project.org/) ###
Почти вся семейка сонаты.

### [FOS User Bundle](https://github.com/FriendsOfSymfony/FOSUserBundle) ###
Для проектов без CRM используется в качестве провайдера пользователей сайта и и персонала админки.

Для проектов с CRM используется **только в качестве провайдера персонала админки**.

### [Nelmio CORS Bundle](https://github.com/nelmio/NelmioCorsBundle) ###
Поддерживает все фичи CORS. В общем случае используется для разрешения кроссдоменных запросов фронтам во время разработки.

### [Nelmio Api Doc Bundle](https://github.com/nelmio/NelmioApiDocBundle) ###
Генерация документации к методам на основе аннотаций.

### [FOS JS Routing Bundle](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle) ###
Позволяет использовать роутинг в JS. Нельзя хардкодить пути к методам в js.

### [ADW Js Context Bundle](https://bitbucket.org/prodhub/js-context-bundle) ###
Вывод данных в контекст js.

### [Vesax SEO Bundle](https://github.com/Vesax/SEOBundle) ###
Настройки метаданных страниц и редиректов в админке.

### [Vesax MaintenanceBundle](https://github.com/Vesax/maintenance-bundle) ###
Генерация страниц тех. работ. Поддержка отсроченного выключения/включения сайта.

Еще много чего. В этом разделе описывать не предназначение каждого бандла, а описывать почему он нужен именно в скелетоне и специфику его использования в скелетоне (если это не очевидно).
***
# Логирование #
Для логов по умолчанию настроена ротация. 

Добавлен канал "domain" с уровнем info, в него рекомендуется логировать доменные события.
```
#!php
$container->get('monolog.logger.domain')->info('User registered ...');
```
Добавлен handler "external" - раскомментировать и настроить каналы если используются удаленные сервисы (CRM, например).

Расширенный вариант rollbar handler'а:

```
#!yaml
adw_common:
    logger:
        rollbar:
            token: ...
#           person_provider: my_awesome_person_provider #Можно указать кастомный провайдер данных пользователя

monolog:
    handlers:
        rollbar:
        type: rollbar
        id: common.logger.rollbar
        level: critical
```
В config_prod.yml все это есть, нужно только раскомментировать.

TODO: купить rollbar.
***
# Безопасность, пользователи #
По умолчанию в комплекте есть FOS User Bundle.

Для проектов без CRM используется в качестве провайдера пользователей сайта и и персонала админки.

Для проектов с CRM используется **только в качестве провайдера персонала админки**.

Подключен административный раздел для пользователей FOS User используя [Sonata User Bundle](https://sonata-project.org/bundles/user/master/doc/reference/installation.html)

Для разграничения доступа используются группы. К группам привязаны роли. 
Список доступных ролей формируется используя RoleProvider. По умолчанию есть SonataRoleProvider, который сформирует список ролей на основе доступных админ - разделов. 

[Как добавить свой RoleProvider ](https://github.com/Vesax/AdminExtraBundle/blob/master/README.md) 
***
# Админка #
Админка доступна по адресу /admin
***
# Установка и запуск #
1. Форкнуть этот репозиторий, создав репозиторий для проекта в команде prodhub.
2. Клонировать созданный репозиторий
3. Выполнить (только в dev окружении!)

```
#!bash
bin/setup.sh
```

Запуск с нативным php:

```
#!bash

php app/console server:run
```
Запуск на vagrant:

```
#!bash
vagrant up
```
***
# Код стайл #
Для приведения кода в нормальный вид выполнить:

```
#!bash

bin/php-cs-fixer fix src
```

***

# Деплой 

[Деплой используя Capistrano](docs/deploy/capistrano.md) 

***

# Развитие скелетона #
Цели разработки и использования скелетона:

* Максимально быстрая инициализация нового проекта
* Общая архитектура проектов
* Навязывание определенных общих подходов и стиля

Это должно способствовать повышению скорости и качества разработки, а так же облегчить поддержку.

Скелетон должен стать базой для всех новых проектов. 

Развивать проект можно отправкой pull request с форкнутых проектов. (Напрямую коммитить если если уверенность что оно нужно и ничего не сломает)
***
### TODO ###
* Больше доки. Вместо одного readme сделать папку docs с докой по категориям.

[В Brains](http://brains.production.adwatch.ru/backend/skeleton)