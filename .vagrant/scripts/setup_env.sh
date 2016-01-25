#!/bin/bash

hostname $1

# @see http://symfony.com/blog/new-in-symfony-2-7-phpunit-bridge
sed -i '/SYMFONY_DEPRECATIONS_HELPER/d' /etc/environment && echo "export SYMFONY_DEPRECATIONS_HELPER=weak" >> /etc/environment
sed -i '/PROJECT_NAME/d' /etc/environment && echo "export PROJECT_NAME=$2" >> /etc/environment
sed -i '/APP_PATH/d' /etc/environment && echo "export APP_PATH=/data/sites/$2" >> /etc/environment

if [ ! -f /home/vagrant/.ssh/id_rsa ]; then
    ssh-keygen -b 2048 -t rsa -f /home/vagrant/.ssh/id_rsa -q -N ""
    chown vagrant:vagrant /home/vagrant/.ssh/id_rsa*
fi

source /etc/environment

#https://www.digitalocean.com/community/tutorials/how-to-add-swap-on-centos-6
if [ $(swapon -s | grep "/swapfile" | wc -l) -eq 0 ]; then
    sudo dd if=/dev/zero of=/swapfile bs=1024 count=1024k
    mkswap /swapfile
    swapon /swapfile

    echo "/swapfile swap swap defaults 0 0" >> /etc/fstab
fi