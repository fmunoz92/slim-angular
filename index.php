<?php

require 'vendor/autoload.php';
require 'bootstrap.php';//have $em
require 'models/article.php';//Article Model and repository

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
        $all = $em->getRepository('Article')->findAll();
        $result = array();
        foreach ($all as $key => $article) {
            $result[] = $article->toArray();
        }

        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->setBody(json_encode($result));
    });

    // Post create article
    $app->post('/', function () use ($app, $em) {
        $reqBody = json_decode($app->request->getBody(), true);

        $article = new Article();
        $article->fromArray($reqBody);

        $em->persist($article);
        $em->flush();

        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->setBody(json_encode($article->toArray()));
    });

    // Get article with ID
    $app->get('/:id', function ($id) use ($app, $em) {
        $article = $em->getRepository('Article')->find($id);
        if(!is_null($article)) {
            $app->response->headers->set('Content-Type', 'application/json');
            $app->response->setBody(json_encode($article->toArray()));
        }
    });


    // Update article with ID
    $app->put('/:id', function ($id) use ($app, $em) {
        $reqBody = json_decode($app->request->getBody(), true);

        $article = $em->getRepository('Article')->find($id);

        if (!is_null($article)) {
            $article->fromArray($reqBody);

            $em->merge($article);
            $em->flush();
            $app->response->headers->set('Content-Type', 'application/json');
            $app->response->setBody(json_encode($article->toArray()));
        }
    });

    // Delete article with ID
    $app->delete('/:id', function ($id) use ($app, $em) {
        $article = $em->getRepository('Article')->find($id);

        if (!is_null($article)) {
            $em->remove($article);
            $em->flush();
            $app->response->headers->set('Content-Type', 'application/json');
            $app->response->setBody(json_encode($article->toArray()));
        }
    });

});


$app->run();