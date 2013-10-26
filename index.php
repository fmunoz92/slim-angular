<?php

require 'vendor/autoload.php';
require 'bootstrap.php';//have $em
require 'models/article.php';//Article Model

$app = new \Slim\Slim();

$app->config(array(
    'debug' => DEBUG,
    'templates.path' => 'views'
));

$app->get('/', function() use ($app) {
    $app->render("index.php");
});

// articles group
$app->group('/articles', function () use ($app, $em) {

    // Get all articles
    $app->get('/', function () use ($app, $em) {
        $all = Article::createQuery('a')->getArrayResult();
        $app->response->setBody(json_encode($all));
    });

    // Post create article
    $app->post('/', function () use ($app, $em) {
        $article = Article::createFromJson($app->request->getBody());
        $article->persist();
        $em->flush();
        $app->response->setBody($article->toJson());
    });

    // Get article with ID
    $app->get('/:id', function ($id) use ($app, $em) {
        $article = Article::find($id);
        $app->response->setBody($article->toJson());
    });


    // Update article with ID
    $app->put('/:id', function ($id) use ($app, $em) {
        $article = Article::find($id);
        $article->updateFromJson($app->request->getBody());
        $em->flush();
        $app->response->setBody($article->toJson());
    });

    // Delete article with ID
    $app->delete('/:id', function ($id) use ($app, $em) {
        $article = Article::find($id);
        $em->remove($article);
        $em->flush();
        $app->response->setBody($article->toJson());
    });
});


$app->run();