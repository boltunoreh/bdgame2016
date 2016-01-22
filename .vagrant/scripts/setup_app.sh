#!/bin/bash

source /etc/environment

sudo /etc/init.d/httpd graceful

cd ~ && rm -f website && ln -s "$APP_PATH/public" website
cd ~/website

composer config -g github-oauth.github.com 5260f87b04f608b43d06d4b51bd8c6577add4b70
composer install --prefer-dist --no-interaction

bin/robo deploy:vagrant