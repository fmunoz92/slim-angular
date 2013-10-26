<?php

$lib = 'path/to/doctrine2/';
require $lib . 'lib/Doctrine/ORM/Tools/Setup.php';

Setup::registerAutoloadGit($lib);

$cache = new \Doctrine\Common\Cache\ArrayCache;

$config = Setup::createAnnotationMetadataConfiguration(array(), true);
$config->setSQLLogger(new Doctrine\DBAL\Logging\EchoSQLLogger());

$connectionOptions = array(
    'driver' => 'pdo_sqlite',
    'memory' => true,
);

$evm = new \Doctrine\Common\EventManager();
$evm->addEventListener(array('postLoad'), new ActiveEntityListener);

$em = EntityManager::create($connectionOptions, $config, $evm);
ActiveEntityRegistry::setDefaultManager($em);

$schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
$schemaTool->createSchema(array(
    $em->getClassMetadata("Article")
));

$article = new Article();
$article->setHeadline("foo");
$article->setBody("barz!");

$other = Article::create(array('headline' => 'foo', 'body' => 'omg!?'));

$article->persist();
$other->persist();

$em->flush();
$em->clear();

$article = Article::find(1);
$article->remove();

$em->flush();

$articles = Article::findBy(array('headline' => 'foo'));
echo count($articles) . " articles\n";

$articles = Article::createQueryBuilder('r')->where(
    Article::expr()->like("r.body", '%omg%')
);
echo count($articles) . " articles\n";