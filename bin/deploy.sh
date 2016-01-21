#!/bin/bash

php app/console doctrine:database:create --if-not-exists
php app/console assets:install
php app/console cache:clear -e prod