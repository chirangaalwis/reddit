<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('reddit');
        $this->load->library("pagination");
    }

    /**
     * Routes the user to the index page of the system.
     */
    public function index() {
        $data = $this->config_pagination();
        $this->load->view('frontpage', $data);
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
                $retreived_id = $registered[0]->user_id;
                $retreived_username = $registered[0]->user_username;
                $retreived_email = $registered[0]->user_email;

                //  set session user data
                $this->session->set_userdata('loggedin', 1);
                $this->session->set_userdata('user_id', $retreived_id);
                $this->session->set_userdata('username', $retreived_username);
                $this->session->set_userdata('email', $retreived_email);


                $this->index();
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

        $this->index();
    }

    public function logout() {
        $this->session->sess_destroy();
        $this->index();
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

            $this->index();
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

    public function display_post() {
        $post_id = filter_input(INPUT_POST, 'post');

        if (isset($post_id)) {
            $data = array("post_object" => get_post($post_id));
            $this->load->view('postpage', $data);
        }
    }

    public function upvote_post() {
        $post_id = filter_input(INPUT_POST, 'post');
        $upvotes = filter_input(INPUT_POST, 'upvotes');

        if (isset($post_id) && isset($upvotes)) {
            vote_post($post_id, $upvotes + 1, 'up');
            $data = array("post_object" => get_post($post_id));
            $this->load->view('postpage', $data);
        }
    }

    public function downvote_post() {
        $post_id = filter_input(INPUT_POST, 'post');
        $downvotes = filter_input(INPUT_POST, 'downvotes');

        if (isset($post_id) && isset($downvotes)) {
            vote_post($post_id, $downvotes + 1, 'down');
            $data = array("post_object" => get_post($post_id));
            $this->load->view('postpage', $data);
        }
    }

    public function delete_post() {
        $post_id = filter_input(INPUT_POST, 'post');
        $type = filter_input(INPUT_POST, 'type');

        delete_post($post_id, $type);

        $this->index();
    }

    public function add_comment() {
        $post = filter_input(INPUT_POST, 'post_id');
        $comment_text = filter_input(INPUT_POST, 'comment');
        $comment_parent = filter_input(INPUT_POST, 'parent_comment');

        if (isset($post) && isset($comment_text) && isset($comment_parent)) {
            $datetime = date('Y-m-d H:i:s');
            store_comment($comment_text, $datetime, $comment_parent, $post);

            $data = array("post_object" => get_post($post));
            $this->load->view('postpage', $data);
        } else {
            $data = array("post_id" => $post, "parent_comment" => $comment_parent);
            $this->load->view('comment', $data);
        }
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

    public function config_pagination() {
        $config = array();
        $config["base_url"] = "http://" . gethostname() . "/reddit/index.php/welcome/index";
        $config["total_rows"] = get_post_count();
        $config["per_page"] = 5;
        $config["num_links"] = 3;

        //  link formatting
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";

        $this->pagination->initialize($config);

        $data = array();
        $data["posts"] = get_posts($config["per_page"], $this->uri->segment(3));
        $data["links"] = $this->pagination->create_links();

        return $data;
    }

}
