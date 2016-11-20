<?php

/**
 * A PHP class which represents a user post.
 * 
 * @author Chiranga Alwis <chirangaalwis@gmail.com>
 */
class Post extends CI_Model {
    /**
     * A representation of the post id.
     * 
     * @var string represents the post id 
     */
    public $id;
    /**
     * A representation of the post title.
     * 
     * @var string represents the post title 
     */
    public $title;
    /**
     * A representation of the number of upvotes for the post.
     * 
     * @var integer represents the number of upvotes for the post
     */
    public $upvotes;
    /**
     * A representation of the number of downvotes for the post.
     * 
     * @var integer represents the number of downvotes for the post
     */
    public $downvotes;
    /**
     * A representation of the date-time at which the post was created.
     * 
     * @var datetime represents the date-time at which the post was created
     */
    public $creation;
    /**
     * A representation of the list of comments received for the post.
     * 
     * @var array represents a list of comments received for the post
     */
    public $comments;
}
