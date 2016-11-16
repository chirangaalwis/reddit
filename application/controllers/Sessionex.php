<?php

class Sessionex extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function index() {
//        $this->load->view('session_view');
        $this->load->view('login');
    }

    public function set() {
        $this->session->set_userdata('name', 'virat');

        $this->load->view('session_view');
    }

    public function remove() {
        //removing session data 
        $this->session->unset_userdata('name');
        $this->load->view('session_view');
    }

}
