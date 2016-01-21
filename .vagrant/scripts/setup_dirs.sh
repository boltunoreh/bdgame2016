#!/bin/bash

mkdir -p $APP_PATH/{private,logs}

chmod 755 $APP_PATH
chmod -R 754 $APP_PATH/private
chmod -R 754 $APP_PATH/logs