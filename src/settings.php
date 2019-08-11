<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        // Allow the web server to send the content-length header
        'addContentLengthHeader' => false,

        // Twig view settings
        'view' => [
            'template_path' => __DIR__ . '/../templates/',
            'cache' => false
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__
                . '/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // SQLite settings
        'db' => [
            'path' => __DIR__ . '/../data/blog.db',
            'driver' => 'sqlite',
            'errorMode' => PDO::ERRMODE_EXCEPTION,
            'fetchMode' => PDO::FETCH_ASSOC
        ]
    ],
];
