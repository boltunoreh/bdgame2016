#!/usr/bin/env bash

php app/console doctrine:migrations:migrate -n -e prod
php app/console assets:install -e prod
php app/console sonata:media:fix-media-context -e prod
PASSWORD=`date +%s|sha256sum|base64|head -c 32`
php app/console fos:user:create admin admin@admin.ru $PASSWORD --super-admin
echo "Admin password: " $PASSWORD;