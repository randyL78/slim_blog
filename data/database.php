<?php

/**
 * Performs the database queries. Essentially the bulk
 * of the "Model" in the MVC pattern
 * 
 * All functions are static, and you can't instantiate this class
 * @author Randy D. Layne
 */
abstract class Database
{
    /**
     * Get all posts from the posts table. 
     * Limit the columns to the title, date, and slug for easy
     * list displaying.
     * @param PDO $db an object representing the database connection
     * @return mixed array containing the posts
     */
    public static function getPosts(PDO $db)
    {
        $sql = 'SELECT title, date, slug FROM posts';

        try {
            $results = $db->query($sql);
        } catch (Exception $ex) {
            echo "Bad query";
            return false;
        }

        return $results->fetchAll();
    }

    /**
     * Get the details of a single post
     * @param PDO $db an object representing the database connection
     * @param string $slug the title of a post in slug form
     * @return mixed array containing the details of the post
     */
    public static function getPost(PDO $db, $slug)
    {
        $sql = 'SELECT * FROM posts WHERE slug = ?';

        try {
            $results = $db->prepare($sql);
            $results->bindValue(1, $slug, PDO::PARAM_STR);
            $results->execute();
            return $results->fetch();
        } catch (Exception $ex) {
            echo $ex;
            return false;
        }
    }
}
