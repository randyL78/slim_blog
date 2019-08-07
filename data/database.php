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
    public static function getStaticPosts(PDO $db)
    {
        $sql = 'SELECT title, date, slug FROM posts';

        try {
            $results = $db->query($sql);
        } catch (Exception $ex) {
            echo "Bad query";
            exit;
        }

        return $results->fetchAll();
    }
}
