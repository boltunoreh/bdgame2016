#!/bin/bash


rm -f /etc/httpd/conf.d/ssl.conf
rm -f /etc/httpd/conf.d/vagrant-ssl.conf && cat > /etc/httpd/conf.d/vagrant-ssl.conf <<EOF
LoadModule ssl_module modules/mod_ssl.so

Listen 443

SSLPassPhraseDialog  builtin

SSLSessionCache         shmcb:/var/cache/mod_ssl/scache(512000)
SSLSessionCacheTimeout  300

SSLMutex default

SSLRandomSeed startup file:/dev/urandom  256
SSLRandomSeed connect builtin

SSLCryptoDevice builtin

<VirtualHost _default_:443>
    SetEnv VAGRANT_ENV true

    ErrorLog "$APP_PATH/logs/apache-ssl-error.log"
    CustomLog "$APP_PATH/logs/apache-ssl-access.log" combined
    LogLevel warn

    ServerAdmin vagrant@production.adwatch.ru
    DocumentRoot "$APP_PATH/public/web"
    DirectoryIndex index.php index.html app.php

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

    SSLEngine on

    SSLProtocol all -SSLv2

    SSLCipherSuite DEFAULT:!EXP:!SSLv2:!DES:!IDEA:!SEED:+3DES

    SSLCertificateFile /etc/pki/tls/certs/localhost.crt
    SSLCertificateKeyFile /etc/pki/tls/private/localhost.key

    SetEnvIf User-Agent ".*MSIE.*" \
             nokeepalive ssl-unclean-shutdown \
             downgrade-1.0 force-response-1.0
</VirtualHost>
EOF