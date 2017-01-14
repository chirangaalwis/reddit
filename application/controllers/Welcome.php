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

    public function sessions() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->login();
        } elseif ($this->input->server('REQUEST_METHOD') == 'GET') {
            $this->index();
        } elseif ($this->input->server('REQUEST_METHOD') == 'DELETE') {
            $this->session->sess_destroy();
            $this->index();
        }
    }

    public function login() {

        $foo = file_get_contents("php://input");
        $ready = json_decode($foo);
        
//        $username = filter_input(INPUT_POST, 'username');
//        $password = filter_input(INPUT_POST, 'password');
        $username = $ready->username;
        $password = $ready->password;

//        if (isset($obj['username']) && isset($obj['password'])) {
        if (isset($username) && isset($password)) {
            $user = new User();
            $user->username = $username;
            $user->password = $password;
            
//            $user->username = $obj['username'];
//            $user->password = $obj['password'];

            if ($user->authenticate()) {
                //  set session user data
                $this->session->set_userdata('loggedin', 1);
                $this->session->set_userdata('user_id', $user->id);
                $this->session->set_userdata('username', $user->username);
                $this->session->set_userdata('email', $user->email_address);

                return;
            }
        }
    }

    public function register() {
        if (isset($_POST['username']) && isset($_POST['password']) && ($_POST['email'])) {
            $username = $_POST['username'];
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

    public function profile() {
        if (isset($this->session->username)) {
            $retreived_id = $this->session->user_id;
            
            $user_comments = get_user_comments($retreived_id);
            
            $mapping_posts = array();
            for ($index = 0; $index < count($user_comments); $index++) {
                $mapping_posts[] = get_post($user_comments[$index]->post_id);
            }
            $data = array("posts" => get_user_posts($retreived_id),
                "comments" => $user_comments, "parent_posts" => $mapping_posts);
            
            $this->load->view('profilepage', $data);
        } else {
            $this->index();
        }
    }

    public function post_votes() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->add_post_vote();
        }
    }

    public function add_post_vote() {
        $post_id = filter_input(INPUT_POST, 'post');
        $vote = filter_input(INPUT_POST, 'type');
        if (isset($post_id) && isset($vote)) {
            vote_post($post_id, $vote);
            $upvote_number = get_post_votes($post_id, 'UPVOTE');
            $downvote_number = get_post_votes($post_id, 'DOWNVOTE');
            $response_data = array('upvotes' => $upvote_number, 'downvotes' => $downvote_number);

            echo json_encode($response_data);
        } else {
            $response_data = array();
            echo json_encode($response_data);
        }
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

    public function delete_post() {
        $post_id = filter_input(INPUT_POST, 'post');
        $type = filter_input(INPUT_POST, 'type');

        delete_post($post_id, $type);

        $this->index();
    }

    public function comment_votes() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->add_comment_vote();
        }
    }

    public function add_comment_vote() {
        $comment_id = filter_input(INPUT_POST, 'comment');
        $vote = filter_input(INPUT_POST, 'type');
        if (isset($comment_id) && isset($vote)) {
            vote_comment($comment_id, $vote);
            $upvote_number = get_comment_votes($comment_id, 'UPVOTE');
            $downvote_number = get_comment_votes($comment_id, 'DOWNVOTE');
            $response_data = array('upvotes' => $upvote_number, 'downvotes' => $downvote_number);

            echo json_encode($response_data);
        } else {
            $response_data = array();
            echo json_encode($response_data);
        }
    }

    public function add_comment() {
        $post = filter_input(INPUT_POST, 'post_id');
        $comment_text = filter_input(INPUT_POST, 'comment');
        $comment_parent = filter_input(INPUT_POST, 'parent_comment');

        if (isset($post) && isset($comment_text)) {
            $datetime = date('Y-m-d H:i:s');
            if (isset($comment_parent)) {
                store_comment($comment_text, $datetime, $comment_parent, $post, $this->session->user_id);
            } else {
                store_comment($comment_text, $datetime, NULL, $post, $this->session->user_id);
            }

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

    private function config_pagination() {
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
