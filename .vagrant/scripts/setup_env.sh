#!/bin/bash

sed -i '/APP_PATH/d' /etc/environment

if [ $(hostname) != "$1" ]; then
    hostname $1
fi

echo "export APP_PATH=\"/data/sites/$2"\" >> /etc/environment

if [ ! -f /home/vagrant/.ssh/id_rsa ]; then
    ssh-keygen -b 2048 -t rsa -f /home/vagrant/.ssh/id_rsa -q -N ""
    chown vagrant:vagrant /home/vagrant/.ssh/id_rsa*
fi

source /etc/environment