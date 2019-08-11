<?php

/**
 * Routing for the SlimBlog app using the Slim Framework
 *
 * @author Randy Layne <randylayn78@gmail.com>
 */

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

// load the Model classes
use SlimBlog\Comment;
use SlimBlog\Blog;

return function (App $app) {
    $container = $app->getContainer();

    // * home route
    $app->get(
        '/',
        function (
            Request $request,
            Response $response,
            array $args
        ) use ($container) {

            // get all posts
            $args['posts'] = Blog::getBlogPosts($this->db);

            // CSRF token name and value
            $nameKey = $this->csrf->getTokenNameKey();
            $valueKey = $this->csrf->getTokenValueKey();
            $args['csrf'] = [
                $nameKey => $request->getAttribute($nameKey),
                $valueKey => $request->getAttribute($valueKey)
            ];

            // Render the home page
            return $container->get('view')->render($response, 'home.twig', $args);
        }
    )->setName('home');

    // * delete post route
    $app->post(
        '/blogs/{slug}/delete',
        function (
            Request $request,
            Response $response,
            array $args
        ) use ($container) {

            Blog::deleteBlogPost($this->db, $args['slug']);

            $url = $this->router->pathFor('home');
            return $response->withStatus(302)->withHeader('Location', $url);
        }
    );

    // * new route
    // * ensure this route is checked before details route
    $app->map(
        ['GET', 'POST'],
        '/blogs/new',
        function (
            Request $request,
            Response $response,
            array $args
        ) use ($container) {

            if ($request->getMethod() == 'POST') {
                $args = array_merge($request->getParsedBody());

                $blog = new Blog(
                    $args['post_title'],
                    null,
                    $args['body']
                );

                if (!empty($args['post_title']) && !empty($args['body'])) {
                    Blog::saveBlogPost($this->db, $blog);

                    // get the id of the Blog that was just saved
                    $blog_id = Blog::getBlogPost(
                        $this->db,
                        $blog->getSlug()
                    )->getId();

                    // save selected tags to database
                    Blog::saveBlogTags($this->db, $blog_id, $args['tags']);

                    $url = $this->router->pathFor('home');
                    return $response->withStatus(302)->withHeader('Location', $url);
                }

                $args['error'] = 'All fields are required';
                $args['post'] = $blog;
            }

            $args['title'] = 'Publish New Entry';
            $args['action'] = "/blogs/new";

            // CSRF token name and value
            $nameKey = $this->csrf->getTokenNameKey();
            $valueKey = $this->csrf->getTokenValueKey();
            $args['csrf'] = [
                $nameKey => $request->getAttribute($nameKey),
                $valueKey => $request->getAttribute($valueKey)
            ];

            // get available tags
            $args['tags'] = Blog::getAllTags($this->db);

            // render new blog post form
            return $container->get('view')->render($response, 'new.twig', $args);
        }
    );

    // * blog details route
    $app->get(
        '/blogs/{slug}',
        function (
            Request $request,
            Response $response,
            array $args
        ) use ($container) {

            // get a specific post
            $args['post'] = Blog::getBlogPost($this->db, $args['slug']);

            // get comments related to post
            $args['comments'] = Comment::getComments(
                $this->db,
                $args['post']->getId()
            );


            // CSRF token name and value
            $nameKey = $this->csrf->getTokenNameKey();
            $valueKey = $this->csrf->getTokenValueKey();
            $args['csrf'] = [
                $nameKey => $request->getAttribute($nameKey),
                $valueKey => $request->getAttribute($valueKey)
            ];

            // render detail page
            return $container->get('view')->render($response, 'detail.twig', $args);
        }
    );

    // * post a comment
    $app->post(
        '/blogs/{slug}',
        function (
            Request $request,
            Response $response,
            array $args
        ) use ($container) {

            // save slug before array merge
            $slug = $args['slug'];

            // get post arguments
            $args = array_merge($request->getParsedBody());

            // Create comment from post request data
            $comment = new Comment($args['name'], $args['comment'], $args['post']);

            if (!empty($args['name']) && !empty($args['comment'])) {
                Comment::saveComment($this->db, $comment);
            } else {
                // save the request data to arguments
                $args['comment'] = $comment;
            }

            // get a specific post
            $args['post'] = Blog::getBlogPost($this->db, $slug);

            // get comments related to post
            $args['comments'] = Comment::getComments(
                $this->db,
                $args['post']->getId()
            );

            // CSRF token name and value
            $nameKey = $this->csrf->getTokenNameKey();
            $valueKey = $this->csrf->getTokenValueKey();
            $args['csrf'] = [
                $nameKey => $request->getAttribute($nameKey),
                $valueKey => $request->getAttribute($valueKey)
            ];

            // render detail page
            return $container->get('view')->render($response, 'detail.twig', $args);
        }
    );

    // * update blog route
    $app->map(
        ['GET', 'POST'],
        '/blogs/{slug}/edit',
        function (
            Request $request,
            Response $response,
            array $args
        ) use ($container) {

            $slug = $args['slug'];

            if ($request->getMethod() == 'POST') {
                $args = array_merge($request->getParsedBody());

                // create a blog object to store post request data
                $blog = new Blog(
                    $args['post_title'],
                    null,
                    $args['body']
                );

                if (!empty($args['post_title']) && !empty($args['body'])) {
                    // save Blog to database
                    Blog::saveBlogPost($this->db, $blog, $slug);

                    // get the id of the Blog that was just saved
                    $blog_id = Blog::getBlogPost($this->db, $slug)->getId();

                    // save selected tags to database
                    Blog::saveBlogTags($this->db, $blog_id, $args['tags']);

                    $url = $this->router->pathFor('home');
                    return $response->withStatus(302)->withHeader('Location', $url);
                }
                $args['error'] = 'All fields are required';
                $args['post'] = $blog;
            }

            $args['title'] = 'Edit Entry';

            // CSRF token name and value
            $nameKey = $this->csrf->getTokenNameKey();
            $valueKey = $this->csrf->getTokenValueKey();
            $args['csrf'] = [
                $nameKey => $request->getAttribute($nameKey),
                $valueKey => $request->getAttribute($valueKey)
            ];

            $args['action'] = "/blogs/$slug/edit";

            // get a specific post
            $args['post'] = Blog::getBlogPost($this->db, $slug);

            // get available tags
            $args['tags'] = Blog::getAllTags($this->db);

            foreach ($args['post']->getTags() as $tag) {
                $index = (array_search($tag, $args['tags']));
                $args['tags'][$index]['selected'] = 'checked';
            }

            // render new blog post form
            return $container->get('view')->render($response, 'new.twig', $args);
        }
    );
};
