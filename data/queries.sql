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

-- Select all blog posts
SELECT * FROM posts;

--Select all comments
SELECT * FROM comments;