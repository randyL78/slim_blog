<?php

/**
 * An object containing information about a single comment
 ** Because of the simplicity of this object/table relationship,
 ** this class follows the Active Record Patter. Had it been a 
 ** more complex relationship, the Data Mapper Pattern would have
 ** been chose.
 * @author Randy D. Layne
 */
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
     * @param string $name The comments author
     * @param string $slug optional The post's title in slug format
     * @param string $body optional The post's body
     * @param string $date optional Date the post was made
     * @param int $id the post's database id   * 
     */
    public function __construct($id, $name, $body, $project_id, $date = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->body = $body;
        $this->project_id = $project_id;

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
     * @param PDO $db an object representing the database connection
     * @param int $id The id of the post
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
                $comment['id'],
                $comment['name'],
                $comment['body'],
                $comment['post_id'],
                $comment['date']
            );
        }

        return $comments;
    }
}
