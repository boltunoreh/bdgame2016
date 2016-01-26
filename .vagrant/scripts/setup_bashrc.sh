#!/bin/bash

source /etc/environment

if [ -n "$PROJECT_NAME" ]; then
    sed -i '/PS1/d' ~/.bashrc && echo "PS1='\[\033[02;32m\]\u@$PROJECT_NAME \[\033[02;37m\]\w\$\[\033[00m\] '" >> ~/.bashrc
    source ~/.bashrc
fi