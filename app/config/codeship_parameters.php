<?php
/**
 * Created by PhpStorm.
 * User: Aleksey Kolyadin
 * Date: 09.07.2015
 * Time: 15:45
 */

/**
 * Меняем настройки подключения к mysql, если тесты выполняются на codeship
 * @see https://codeship.com/documentation/databases/mysql/
 */
if (isset($_ENV['CI']) && $_ENV['CI'] == true) {

    $container->loadFromExtension('doctrine', [
        'dbal' => [
            'host' => 'localhost',
            'dbname' => 'test',//бд test создается автоматически на уровне виртуалки codehsip
            'user' => $_ENV['MYSQL_USER'],//MYSQL_USER - переменная определяется на уровне виртуалки codeship
            'password' => $_ENV['MYSQL_PASSWORD'],//MYSQL_PASSWORD - переменная определяется на уровне виртуалки codeship
        ]
    ]);

}