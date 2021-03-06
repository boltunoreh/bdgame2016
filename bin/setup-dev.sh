#!/usr/bin/env bash

php app/console doctrine:database:create --if-not-exists
php app/console doctrine:schema:drop --force
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load -n
php app/console fos:user:create admin admin@admin.ru admin --super-admin
php app/console sonata:media:fix-media-context