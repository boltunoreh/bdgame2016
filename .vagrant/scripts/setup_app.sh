#!/bin/bash

source /etc/environment

sudo /etc/init.d/httpd graceful

composer config -g github-oauth.github.com 5260f87b04f608b43d06d4b51bd8c6577add4b70

cd ~ && rm -f website && ln -s "$APP_PATH/public" website

cd ~/website && composer install --prefer-dist --no-interaction

if [ -f ~/vendor/phpunit/phpunit/phpunit ]; then
    cd ~ && rm -f phpunit && ln -s ~/vendor/phpunit/phpunit/phpunit phpunit
fi

cd ~/website && bin/robo deploy:vagrant