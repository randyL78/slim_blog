<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

// manually load the Database class
require_once __DIR__ . '/../data/Database.php';

return function (App $app) {
    $container = $app->getContainer();



    // home route
    $app->get('/', function (Request $request, Response $response, array $args) use ($container) {
        // Log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // get all posts
        $args['posts'] = Database::getPosts($container->get('db'));

        // Render the home page
        return $container->get('view')->render($response, 'home.twig', $args);
    });

    // new route
    // * ensure this route is checked before details route
    $app->get('/blogs/new', function (Request $request, Response $response, array $args) use ($container) {
        // Log message
        $container->get('logger')->info("Slim-Skeleton '/blogs/new' route");

        $args['title'] = 'Publish New Entry';

        // render new blog post form
        return $container->get('view')->render($response, 'new.twig', $args);
    });

    // blog details route
    $app->get('/blogs/{slug}', function (Request $request, Response $response, array $args) use ($container) {
        // Log message
        $container->get('logger')->info("Slim-Skeleton '/blogs/id' route");

        // get a specific post
        $args['post'] = Database::getPost($container->get('db'), $args['slug']);

        var_dump($args['post']);


        // render new blog post form
        return $container->get('view')->render($response, 'detail.twig', $args);
    });


    // edit blog route
    $app->get('/blogs/{id}/edit', function (Request $request, Response $response, array $args) use ($container) {
        // Log message
        $container->get('logger')->info("Slim-Skeleton '/blogs/id/edit' route");

        $args['title'] = 'Edit Entry';


        // render new blog post form
        return $container->get('view')->render($response, 'new.twig', $args);
    });
};
