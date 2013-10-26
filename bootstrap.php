<?php

define("DBHOST", '127.0.0.1');
define("DBDRIVER", 'pdo_mysql');
define("DBNAME", 'angularphp');
define("DBUSER", 'root');
define("DBPASS", '1');
define("DEBUG", 1);

// the connection configuration
$dbParams = array(
    'driver'   => DBDRIVER,
    'user'     => DBUSER,
    'password' => DBPASS,
    'dbname'   => DBNAME,
    'database_host' => DBHOST,
    'memory' => true
);

$cache = new \Doctrine\Common\Cache\ArrayCache;
$config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array("models"), DEBUG);
$config->setSQLLogger(new Doctrine\DBAL\Logging\EchoSQLLogger());

$evm = new \Doctrine\Common\EventManager();
$activeEntityListener = new ActiveEntityListener();
$evm->addEventListener(array(Doctrine\ORM\Events::postLoad), $activeEntityListener);

$em = Doctrine\ORM\EntityManager::create($dbParams, $config, $evm);
ActiveEntityRegistry::setDefaultManager($em);
