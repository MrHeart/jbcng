<?php

/**
 * Created by PhpStorm.
 * User: Mr Heart
 * Date: 10/4/2016
 * Time: 4:44 AM
 */
class App extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('adminModel');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }
    public function header(){
        $this->load->view('partials/app_header');
    }

    public function footer(){
        $this->load->view('partials/app_footer');
    }


    public function Index(){
        $this->header();

        $data["subjects"] = $this->adminModel->GetSubjects(1);
        $data["title"] = "Dashboard";
        $this->load->view('pages/app_index', $data);
        $this->footer();
    }
}