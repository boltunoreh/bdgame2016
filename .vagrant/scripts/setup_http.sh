#!/bin/bash

rm -f /etc/httpd/conf.d/vagrant-http.conf && cat > /etc/httpd/conf.d/vagrant-http.conf <<EOF
<VirtualHost *:80>
      SetEnv VAGRANT_ENV true

      ServerAdmin vagrant@production.adwatch.ru
      DocumentRoot "$APP_PATH/public/web"

      DirectoryIndex index.php index.html app.php

      ErrorLog "$APP_PATH/logs/apache-http-error.log"
      CustomLog "$APP_PATH/logs/apache-http-access.log" combined

      <IfModule php5_module>
          php_admin_value upload_tmp_dir "$APP_PATH/public/web"
          php_value upload_max_filesize 8000000
          php_value post_max_size 8000000
          php_value session.save_path "/dev/shm"
      </IfModule>

      <Directory "$APP_PATH/public/web">
          AllowOverride All
          Order allow,deny
          Allow from all
      </Directory>
  </VirtualHost>
EOF