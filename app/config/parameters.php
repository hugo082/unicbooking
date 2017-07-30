<?php
/**
 * Created by PhpStorm.
 * User: hugofouquet
 * Date: 30/07/2017
 * Time: 13:16
 */

$params = [
    'DATABASE_URL',
    'APP',
    'SYMFONY_ENV',
    'SECRET'
];

foreach ($params as $param) {
    if(isset($_ENV[$param])) {
        $container->setParameter(strtolower($param), $_ENV[$param]);
    }
}