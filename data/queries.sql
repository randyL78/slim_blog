-- Add the "slug" column to the posts table
-- ALTER TABLE posts ADD slug text NOT NULL;

-- Add a post --
-- INSERT INTO posts 
-- (title, date, body, slug)
-- VALUES ("The best day I’ve ever had", "2016-01-31 1:00", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ut rhoncus felis, vel tincidunt
--               neque. Vestibulum ut metus eleifend, malesuada nisl at, scelerisque sapien. Vivamus pharetra
--               massa libero, sed feugiat turpis efficitur at.
--           Cras egestas ac ipsum in posuere. Fusce suscipit, libero id malesuada placerat, orci velit
--               semper metus, quis pulvinar sem nunc vel augue. In ornare tempor metus, sit amet congue
--               justo porta et. Etiam pretium, sapien non fermentum consequat, 
--               gravida lacus, non accumsan lorem odio id risus. Vestibulum pharetra tempor molestie.
--               Integer sollicitudin ante ipsum, a luctus nisi egestas eu. Cras accumsan cursus ante, non
--               dapibus tempor.",  "the-best-day-ive-ever-had");

-- create a comment
-- INSERT INTO comments 
-- (name, body, post_id, date)
-- VALUES ("Carling Kirk", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ut rhoncus felis, vel tincidunt neque. Vestibulum ut metus eleifend, malesuada nisl at, scelerisque sapien. Vivamus pharetra massa libero, sed feugiat turpis efficitur at.", 1, CURRENT_TIMESTAMP);

-- Select all blog posts
SELECT * FROM posts;

--Select all comments
SELECT * FROM comments;

--Select a specific post
SELECT * FROM posts WHERE slug = 'the-best-day-ive-ever-had';

-- Update post body
-- UPDATE posts
-- SET body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ut  honcus felis, vel tincidunt neque. Vestibulum ut metus eleifend, malesuada nisl at, scelerisque sapien. Vivamus pharetra massa libero, sed feugiat turpis efficitur at.

-- Cras egestas ac ipsum in posuere. Fusce suscipit, libero id malesuada placerat, orci velit semper metus, quis pulvinar sem nunc vel augue. In ornare tempor metus, sit amet congue justo porta et. Etiam pretium, sapien non fermentum consequat, gravida lacus, non accumsan lorem odio id risus. Vestibulum pharetra tempor molestie.

-- Integer sollicitudin ante ipsum, a luctus nisi egestas eu. Cras accumsan cursus ante, non dapibus tempor.'
-- WHERE id = 1;

-- Create a tag
-- INSERT INTO tags (name) VALUES ('programming');
-- INSERT INTO tags (name) VALUES ('design');
-- INSERT INTO tags (name) VALUES ('personal');
-- INSERT INTO tags (name) VALUES ('misc');
-- INSERT INTO tags (name) VALUES ('instructional');

-- Get all tags
SELECT * FROM tags;

-- Add connections between posts and tags
-- INSERT INTO posts_tags (post_id, tag_id) VALUES (4, 4);
-- INSERT INTO posts_tags (post_id, tag_id) VALUES (2, 1);
-- INSERT INTO posts_tags (post_id, tag_id) VALUES (3, 2);
-- INSERT INTO posts_tags (post_id, tag_id) VALUES (6, 2);
-- INSERT INTO posts_tags (post_id, tag_id) VALUES (1, 4);
-- INSERT INTO posts_tags (post_id, tag_id) VALUES (3, 6);
-- INSERT INTO posts_tags (post_id, tag_id) VALUES (5, 5);
-- INSERT INTO posts_tags (post_id, tag_id) VALUES (2, 3);


-- Get all post-tag connections
SELECT * FROM posts_tags;

-- DROP TABLE "posts_tags";

-- Create tags database
-- CREATE TABLE "posts_tags" (
-- 	"id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
-- 	"post_id"	INTEGER NOT NULL,
-- 	"tag_id"	INTEGER NOT NULL,
--   UNIQUE ("post_id", "tag_id")
-- );