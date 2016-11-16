<?php

/**
 * A PHP class which represents a registered system user.
 * 
 * @author Chiranga Alwis <chirangaalwis@gmail.com>
 */
class User extends CI_Model {
    /**
     * A representation of the user id.
     * 
     * @var string represents the user id 
     */
    public $id;
    /**
     * A representation of the username.
     * 
     * @var string represents the username
     */
    public $username;
    /**
     * A representation of the user password.
     * 
     * @var string represents the user password
     */
    public $password;
    /**
     * A representation of the user email address.
     * 
     * @var string represents the user email address
     */
    public $email_address;
    /**
     * A representation of the list of posts shared by the user.
     * 
     * @var array represents a list of posts shared by the user
     */
    public $posts;
}
