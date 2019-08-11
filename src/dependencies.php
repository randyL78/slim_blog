<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // enable tokens to protect against cross site scripts 
    $container['csrf'] = function ($c) {
        return new \Slim\Csrf\Guard;
    };

    // Register twig view component on container
    $container['view'] = function ($c) {
        $settings = $c->get('settings')['view'];
        $view = new \Slim\Views\Twig(
            $settings['template_path'], [
            'cache' => $settings['cache']
            ]
        );

        // Instantiate and add Slim specific extension
        $router = $c->get('router');
        $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
        $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

        return $view;
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    // database connection
    $container['db'] = function ($c) {
        $settings = $c->get('settings')['db'];
        $pdo = new PDO($settings['driver'] . ':' . $settings['path']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, $settings['errorMode']);
        $pdo->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            $settings['fetchMode']
        );
        return $pdo;
    };
};
