<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('reddit');
    }

    /**
     * Routes the user to the index page of the system.
     */
    public function index() {
//        $data = array("key" => get_posts());
        $this->load->view('frontpage');
    }

    public function register() {
        if (isset($_POST['username']) && isset($_POST['password']) && ($_POST['email'])) {
            $username = $_POST['username'];


            //  TODO: Do password hashing
            $password = $_POST['password'];


            $email_address = $_POST['email'];

            //  insert data to secondary storage
            $data = array('user_username' => $username, 'user_password' => $password,
                'user_email' => $email_address);
            $this->db->insert("user", $data);

            //  check the user id
            $this->db->from('user');
            $this->db->where('user_username', $username);
            $this->db->where('user_password', $password);
            $registered = $this->db->get()->result();

            if (is_array($registered) && count($registered) == 1) {
                $retreived_id = $login[0]->user_id;
                $retreived_username = $login[0]->user_username;
                $retreived_email = $login[0]->user_email;

                //  set session user data
                $this->session->set_userdata('loggedin', 1);
                $this->session->set_userdata('user_id', $retreived_id);
                $this->session->set_userdata('username', $retreived_username);
                $this->session->set_userdata('email', $retreived_email);

//                $data = array("key" => get_posts());
                $this->load->view('frontpage');
            } else {

                //  TODO: Some error message
                $this->load->view('register');
            }
        } else {
            $this->load->view('register');
        }
    }

    public function login() {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];

            //  TODO: Do password hashing
            $password = $_POST['password'];

            $this->db->from('user');
            $this->db->where('user_username', $username);
            $this->db->where('user_password', $password);
            $login = $this->db->get()->result();

            if (is_array($login) && count($login) == 1) {
                $retreived_id = $login[0]->user_id;
                $retreived_username = $login[0]->user_username;
                $retreived_email = $login[0]->user_email;

                //  set session user data
                $this->session->set_userdata('loggedin', 1);
                $this->session->set_userdata('user_id', $retreived_id);
                $this->session->set_userdata('username', $retreived_username);
                $this->session->set_userdata('email', $retreived_email);
            } else {

                //  TODO: Some error message
            }
        }

//        $data = array("key" => get_posts());
        $this->load->view('frontpage');
    }

    public function logout() {
        $this->session->sess_destroy();
        $this->login();
    }

    public function share_post() {
        $title = filter_input(INPUT_POST, 'title');
        $type = filter_input(INPUT_POST, 'type');

        if (isset($title) && isset($type)) {
            $date = date('Y-m-d H:i:s');
            if ($type === 'text') {
                store_post($title, $date, 'text', filter_input(INPUT_POST, 'text'));
            } elseif ($type === 'link') {
                store_post($title, $date, 'link', filter_input(INPUT_POST, 'link'));
            }

//            $data = array("key" => get_posts());
            $this->load->view('frontpage');
        } else {
            $page = filter_input(INPUT_GET, 'page');
            if (isset($page) && $page === 'text') {
                $this->load->view('share_text');
            } elseif (isset($page) && $page === 'link') {
                $this->load->view('share_link');
            } else {
                $this->load->view('frontpage');
            }
        }
    }

    public function vote_post() {
        $post = filter_input(INPUT_POST, 'post');

        if (isset($post)) {
            $data = array("post_object" => $post);
            $this->load->view('postpage', $data);
            vote_post($post->id, $post->votes);
        }
    }

    public function delete_post() {
        $id = filter_input(INPUT_POST, 'id');
        $type = filter_input(INPUT_POST, 'type');

        delete_post($id, $type);

//        $data = array("key" => get_posts());
        $this->load->view('frontpage');
    }

    public function add_comment() {
        $post = filter_input(INPUT_POST, 'post');
        $comment_text = filter_input(INPUT_POST, 'text');
        $comment_parent = filter_input(INPUT_POST, 'parent');

        $datetime = date('Y-m-d H:i:s');
        store_comment($comment_text, $datetime, $comment_parent, $post->id);
        array_push($post->comments, get_comment($comment_text, $datetime, $post->id));

//        $data = array("key" => $post);
        $this->load->view('postpage');
    }

    public function vote_comment() {
        $post = filter_input(INPUT_POST, 'post');
        $comment_id = filter_input(INPUT_POST, 'comment_id');

        vote_comment($comment_id, $post->comments[index]->votes);
//        $data = array("key" => $post);
        $this->load->view('postpage');
    }

    public function delete_comment() {
        $post = filter_input(INPUT_POST, 'post');
        $comment_id = filter_input(INPUT_POST, 'comment_id');

        $index = -1;
        foreach ($post->comments as $comment) {
            $index++;
            if ($comment->id === $comment_id) {
                break;
            }
        }
        unset($post->comments[$index]);

        delete_comment($comment_id);

        //        $data = array("key" => $post);
        $this->load->view('postpage');
    }

//    public function editPost($param) {
//        if (isset($_POST['id']) && isset($_POST['title']) && (isset($_POST['text']) || isset($_POST['link']))) {
//            $id = $_POST['id'];
//            $title = $_POST['title'];
//
//            $post_data = array('post_title' => $title);
//            $this->db->where('post_id', $id);
//            $this->db->update('post', $post_data);
//
//            if (isset($_POST['text'])) {
//                $text = $_POST['text'];
//                $text_data = array('post_text' => $text);
//                $this->db->where('post_id', $id);
//                return $this->db->update('text_post', $text_data);
//            } else {
//                $link = $_POST['link'];
//                $link_data = array('post_link' => $text);
//                $this->db->where('post_id', $id);
//                return $this->db->update('link_post', $link_data);
//            }
//        } else {
//            return false;
//        }
//    }
}
