#!/bin/bash

echo -n "Installing php"

CURRENT_PHP_VERSION=$(php -v | sed -e )

exit 1
yum remove -y "php*"

case $1 in
    54 )
        yum install -y php54w php54w-{common,cli,gd,intl,mbstring,mysqlnd,pdo,soap,xml} ;;
    55 )
        yum install -y php55w php55w-{common,cli,gd,intl,mbstring,mysqlnd,pdo,pecl-apcu,pecl-imagick,soap,xml} ;;
    56 )
        yum install -y php56w php56w-{common,cli,gd,intl,mbstring,mysqlnd,pdo,pecl-apcu,pecl-imagick,soap,xml} ;;
    70 )
        yum install -y php70w php70w-{common,cli,gd,intl,mbstring,mysqlnd,pdo,pecl-apcu,pecl-imagick,soap,xml,opcache} ;;
esac



/etc/init.d/httpd restart