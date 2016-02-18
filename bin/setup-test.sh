#!/usr/bin/env bash

php app/console doctrine:schema:drop --force -e test
php app/console doctrine:schema:update --force -e test
php app/console doctrine:fixtures:load -n -e test
php app/console fos:user:create admin admin@admin.ru admin --super-admin -e test
php app/console sonata:media:fix-media-context -e test