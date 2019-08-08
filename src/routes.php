<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

// load the Model classes
require_once __DIR__ . '/../data/Comment.php';
require_once __DIR__ . '/../data/Blog.php';


return function (App $app) {
    $container = $app->getContainer();

    // home route
    $app->get('/', function (Request $request, Response $response, array $args) use ($container) {
        // Log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // get all posts
        $args['posts'] = Blog::getBlogPosts($container->get('db'));

        // Render the home page
        return $container->get('view')->render($response, 'home.twig', $args);
    })->setName('home');

    // new route
    // * ensure this route is checked before details route
    $app->get('/blogs/new', function (Request $request, Response $response, array $args) use ($container) {
        // Log message
        $container->get('logger')->info("Slim-Skeleton '/blogs/new' route");

        $args['title'] = 'Publish New Entry';

        // render new blog post form
        return $container->get('view')->render($response, 'new.twig', $args);
    })->setName('new-blog');

    // create new post
    $app->post('/blog/new', function (Request $request, Response $response, array $args) use ($container) {

        // CSRF token name and value
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $args['csrf'] = [
            $nameKey => $request->getAttribute($nameKey),
            $valueKey => $request->getAttribute($valueKey)
        ];
    });

    // blog details route
    $app->get('/blogs/{slug}', function (Request $request, Response $response, array $args) use ($container) {
        // Log message
        $container->get('logger')->info("Slim-Skeleton '/blogs/id' route");

        // get a specific post
        $args['post'] = Blog::getBlogPost($container->get('db'), $args['slug']);

        // get comments related to post
        $args['comments'] = Comment::getComments(
            $container->get('db'),
            $args['post']->getId()
        );

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
