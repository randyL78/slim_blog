<?php

/**
 * An object containing information about a single blog
 ** Because of the simplicity of this object/table relationship,
 ** this class follows the Active Record Patter. Had it been a 
 ** more complex relationship, the Data Mapper Pattern would have
 ** been chose.
 * @author Randy Layne
 */

class Blog
{

    // -----------------------------------------------------------
    //        properties
    // -----------------------------------------------------------
    private $id;
    private $title;
    private $date;
    private $body;
    private $slug;

    // -----------------------------------------------------------
    //        methods
    // -----------------------------------------------------------
    /**
     * Constructor
     * @param string $title The post's title
     * @param string $slug optional The post's title in slug format
     * @param string $body optional The post's body
     * @param string $date optional Date the post was made
     * @param int $id the post's database id   * 
     */
    public function __construct($title, $slug = null, $body = null, $date = null, $id = null)
    {
        $this->title = $title;

        if (empty($slug)) {
            $this->slug = $this->slugThis($title);
        } else {
            $this->slug = $slug;
        }

        $this->body = $body;

        if (empty($date)) {
            $this->date = date("Y-m-d H:i:s");
        } else {
            $this->date = $date;
        }

        $this->id = $id;
    }

    /**
     * @return string the post's body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string the post's date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return int the post's id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string the post's title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string the post's slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get all posts from the posts table. 
     * Limit the columns to the title, date, and slug for easy
     * list displaying.
     * @param PDO $db an object representing the database connection
     * @return mixed array containing the posts
     */
    public static function getBlogPosts(PDO $db)
    {
        $sql = 'SELECT title, date, slug FROM posts';

        try {
            $results = $db->query($sql);
        } catch (Exception $ex) {
            echo "Bad query";
            return false;
        }

        $posts = [];

        // create a Blog object for each entry and store in posts array
        foreach ($results->fetchAll() as $post) {
            $posts[] = new Blog(
                $post['title'],
                $post['slug'],
                null,
                $post['date'],
                null
            );
        }

        return $posts;
    }

    /**
     * Get the details of a single post
     * @param PDO $db an object representing the database connection
     * @param string $slug the title of a post in slug form
     * @return mixed array containing the details of the post
     */
    public static function getBlogPost(PDO $db, $slug)
    {
        $sql = 'SELECT * FROM posts WHERE slug = ?';

        try {
            $results = $db->prepare($sql);
            $results->bindValue(1, $slug, PDO::PARAM_STR);
            $results->execute();
            $post = $results->fetch();
            return new Blog($post['title'], $post['slug'], $post['body'], $post['date'], $post['id']);
        } catch (Exception $ex) {
            echo $ex;
            return false;
        }
    }

    /**
     * Save a blog post to the database
     * @param PDO $db an object representing the database connection
     * @param Blog $blog the blog post to save
     * @return bool if the post successfully saved
     */
    public static function saveBlogPost(PDO $db, Blog $blog)
    {
        $sql = 'INSERT INTO posts (title, date, body, slug)'
            .  'VALUES (?, ?, ?, ?)';

        try {
            $results = $db->prepare($sql);
            $results->bindValue(1, $blog->getTitle(), PDO::PARAM_STR);
            $results->bindValue(2, $blog->getDate(), PDO::PARAM_STR);
            $results->bindValue(3, $blog->getBody(), PDO::PARAM_STR);
            $results->bindValue(4, $blog->getSlug(), PDO::PARAM_STR);
            $results->execute();
            return true;
        } catch (Exception $ex) {
            echo $ex;
            return false;
        }
    }

    /**
     * Turn a string into a slug
     * @param string $str string to turn into slug
     * @return string the slug
     */
    public static function slugThis($str)
    {
        return strtolower(trim(preg_replace(
            '/[^A-Za-z0-9-]+/',
            '-',
            $str
        ), '-'));
    }
}
