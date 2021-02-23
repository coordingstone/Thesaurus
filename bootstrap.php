<?php

use DI\Container;

require_once('vendor/autoload.php');

$config = include('config.php');
$builder = new DI\ContainerBuilder();
$builder->addDefinitions(array(
    'PDO' => function (Container $c) use ($config) {
        return new PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['dbName'] . ";port=" . $config['port'], $config['user'], $config['password']);
    }
));
$container = $builder->build();
