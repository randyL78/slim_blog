# Slim Blog

A simple blog app using PHP, built with the help of the Slim microframework and the Twig templating engine.

## Installation

From the command line, if you have composer installed globally run the command `composer install` and then point your server to the `public` sub-folder.

## Instructions

- [X] Use the supplied mockup files to build a personal blog.
- [X] Use composer to install Slim and its dependencies and create the directory and package structure of the application. Save all static assets into the proper directory.
- [X] Create a PDO for data storage to access your databaset.
- [X] Create model classes for blog entries and blog comments.
- [ ] Add necessary routes
- [X] Create index view as the homepage. This view contains a list of blog entries, which displays Title, Date/Time Created. Title should be hyperlinked to the detail page for each blog entry. Include a link to add an entry.
- [X] Create detail page displaying blog entry and submitted comments. Detail page should also display a comment form with Name and Comment. Include a link to edit the entry.
- [X] Create add/edit page for blog posts.
- [X] Use CSS to style headings, font colors, blog entry container colors, body colors.

## Extra Credit

- [ ] Add tags to blog entries, which enables the ability to categorize. A blog entry can exist with no tags, or have multiple tags.
- [ ] Add the ability to delete a blog entry.
- [X] Route blog entries to sear engine friendly post slugs (post title) instead of ID.