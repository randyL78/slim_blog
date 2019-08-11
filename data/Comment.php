<?php

/**
 * An object containing information about a single comment
 * * Because of the simplicity of this object/table relationship,
 * * this class follows the Active Record Patter. Had it been a
 * * more complex relationship, the Data Mapper Pattern would have
 * * been chose.
 *
 * @author Randy D. Layne
 */

namespace SlimBlog;

use PDO;

class Comment
{
    // -----------------------------------------------------------
    //        properties
    // -----------------------------------------------------------
    private $id;
    private $name;
    private $body;
    private $post_id;
    private $date;

    // -----------------------------------------------------------
    //        methods
    // -----------------------------------------------------------
    /**
     * Constructor
     *
     * @param string $name    The comments author
     * @param string $body    The comment
     * @param string $post_id The post related to the comment
     * @param int    $id      the comment's database id   *
     * @param string $date    optional Date the comment was made
     */
    public function __construct($name, $body, $post_id, $id = null, $date = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->body = $body;
        $this->post_id = $post_id;

        if (empty($date)) {
            $this->date = date("Y-m-d H:i:s");
        } else {
            $this->date = $date;
        }
    }

    /**
     * @return string the comment's body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string the comment's date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return int the comment's id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string the comment's name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string the comment's post_id
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * Get the comments related to a post
     *
     * @param  PDO $db an object representing the database connection
     * @param  int $id The id of the post
     * @return mixed array containg the comments
     */
    public static function getComments(PDO $db, int $id)
    {
        $sql = 'SELECT * FROM comments WHERE post_id = ?';

        try {
            $results = $db->prepare($sql);
            $results->bindValue(1, $id, PDO::PARAM_STR);
            $results->execute();
        } catch (Exception $ex) {
            echo $ex;
            return false;
        }

        $comments = [];

        // create a Comment object for each entry and store in comments array
        foreach ($results->fetchAll() as $comment) {
            $comments[] = new Comment(
                $comment['name'],
                $comment['body'],
                $comment['post_id'],
                $comment['id'],
                $comment['date']
            );
        }

        return $comments;
    }

    /**
     * Saves a comment to a database
     *
     * @param  PDO     $db      an object representing the database connection
     * @param  Comment $comment The comment to save
     * @return bool if successfully saved
     */
    public static function saveComment(PDO $db, Comment $comment)
    {
        $sql = 'INSERT INTO comments (name, body, post_id, date)'
            .  'VALUES(?, ?, ?, ?)';


        try {
            $results = $db->prepare($sql);
            $results->bindValue(1, $comment->getName(), PDO::PARAM_STR);
            $results->bindValue(2, $comment->getBody(), PDO::PARAM_STR);
            $results->bindValue(3, $comment->getPostId(), PDO::PARAM_STR);
            $results->bindValue(4, $comment->getDate(), PDO::PARAM_STR);
            $results->execute();
            return true;
        } catch (Exception $ex) {
            echo $ex;
            return false;
        }
    }
}
