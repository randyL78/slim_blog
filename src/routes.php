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
        $args['posts'] = Blog::getBlogPosts($this->db);

        // Render the home page
        return $container->get('view')->render($response, 'home.twig', $args);
    })->setName('home');

    // new route
    // * ensure this route is checked before details route
    $app->get('/blogs/new', function (Request $request, Response $response, array $args) use ($container) {
        // Log message
        $container->get('logger')->info("Slim-Skeleton '/blogs/new' route");

        $args['title'] = 'Publish New Entry';

        // CSRF token name and value
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $args['csrf'] = [
            $nameKey => $request->getAttribute($nameKey),
            $valueKey => $request->getAttribute($valueKey)
        ];

        // render new blog post form
        return $container->get('view')->render($response, 'new.twig', $args);
    })->setName('new-blog');

    // create new post
    $app->post('/blogs/new', function (Request $request, Response $response, array $args) use ($container) {

        $args = array_merge($request->getParsedBody());



        if (!empty($args['post_title']) && !empty($args['body'])) {

            Blog::saveBlogPost($this->db, new Blog(
                $args['post_title'],
                null,
                $args['body']
            ));

            $url = $this->router->pathFor('home');
            return $response->withStatus(302)->withHeader('Location', $url);
        }

        $args['error'] = 'All fields are required';
        $args['title'] = 'Publish New Entry';

        // CSRF token name and value
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $args['csrf'] = [
            $nameKey => $request->getAttribute($nameKey),
            $valueKey => $request->getAttribute($valueKey)
        ];

        // render new blog post form
        return $container->get('view')->render($response, 'new.twig', $args);
    });

    // blog details route
    $app->get('/blogs/{slug}', function (Request $request, Response $response, array $args) use ($container) {
        // Log message
        $container->get('logger')->info("Slim-Skeleton '/blogs/id' route");

        // get a specific post
        $args['post'] = Blog::getBlogPost($this->db, $args['slug']);

        // get comments related to post
        $args['comments'] = Comment::getComments(
            $this->db,
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
