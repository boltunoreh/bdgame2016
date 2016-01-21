#!/bin/bash

source /etc/environment

sudo /etc/init.d/httpd graceful

cd ~ && rm -f website && ln -s "$APP_PATH/public" website
cd ~/website

bin/robo deploy:vagrant