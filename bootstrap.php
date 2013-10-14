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
);

$config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array("models"), DEBUG);

$em = Doctrine\ORM\EntityManager::create($dbParams, $config);