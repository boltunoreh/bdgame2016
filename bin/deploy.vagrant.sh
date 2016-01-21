#!/bin/bash

cd ~/website

exec ./bin/deploy.sh

php app/console cache:clear -e dev