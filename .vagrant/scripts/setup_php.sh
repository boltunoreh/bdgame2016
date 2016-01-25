#!/bin/bash

PHP_VERSION_REQUIRED=$1

CURRENT_PHP_VERSION=$($(which php) -r "echo PHP_MAJOR_VERSION,PHP_MINOR_VERSION;")


if [ $CURRENT_PHP_VERSION -ne $PHP_VERSION_REQUIRED ]; then

    echo -n "Updating to php $PHP_VERSION_REQUIRED"

    yum remove -y "php*"

    case $PHP_VERSION_REQUIRED in
        54 )
            yum install -y php54w php54w-{common,cli,gd,intl,mbstring,mcrypt,mysqlnd,pdo,soap,xml} ;;
        55 )
            yum install -y php55w php55w-{common,cli,gd,intl,mbstring,mcrypt,mysqlnd,pdo,pecl-apcu,pecl-imagick,soap,xml} ;;
        56 )
            yum install -y php56w php56w-{common,cli,gd,intl,mbstring,mcrypt,mysqlnd,pdo,pecl-apcu,pecl-imagick,soap,xml} ;;
        7|70 )
            yum install -y php70w php70w-{common,cli,gd,intl,mbstring,mcrypt,mysqlnd,pdo,pecl-apcu,pecl-imagick,soap,xml,opcache} ;;
    esac

    /etc/init.d/httpd restart

fi

exit 1