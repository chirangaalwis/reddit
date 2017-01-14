<?php

/**
 * A PHP class which represents a comment.
 *
 * @author Chiranga Alwis <chirangaalwis@gmail.com>
 */
class Comment extends CI_Model {

    /**
     * A representation of the comment id.
     * 
     * @var string represents the comment id 
     */
    public $id;

    /**
     * A representation of the comment text.
     * 
     * @var string represents the comment text
     */
    public $text;

    /**
     * A representation of the number of upvotes for the comment.
     * 
     * @var integer represents the number of upvotes for the comment
     */
    public $upvotes;

    /**
     * A representation of the number of downvotes for the comment.
     * 
     * @var integer represents the number of downvotes for the comment
     */
    public $downvotes;

    /**
     * A representation of the date-time at which the comment was created.
     * 
     * @var datetime represents the date-time at which the comment was created
     */
    public $creation;

    /**
     * A representation of the parent comment id.
     * 
     * @var string represents the parent comment id
     */
    public $parent_id;

    /**
     * A representation of the post id to which the comment belongs.
     * 
     * @var string represents the post id 
     */
    public $post_id;

    /**
     * A representation of the user id of the user who created the comment.
     * 
     * @var string represents the user id
     */
    public $user_id;

}
