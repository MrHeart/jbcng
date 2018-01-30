<?php

/**
 * Created by PhpStorm.
 * User: Mr Heart
 * Date: 8/15/2016
 * Time: 6:25 AM
 */
class Admins extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('adminModel');
        $this->load->helper('url_helper');
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
    }

    public function Index()
    {

    }


    public function Add()
    {
        $data = array();

        if (isset($_POST["create"])) {
            $adminId =  $this->adminModel->AddAdmin();
            if(!empty($adminId) && $adminId != 0){
                $adminAccess = $this->adminModel->AdminAccess($adminId);
                if($adminAccess){
                    $data["message"] = "Admin user created successfully!";
                }
            }

        }
        $data["title"] = "View Authors";
        //$data["authors"] = $this->adminModel->getAuthors();
        $this->layout->view('pages/admin_add', $data);
    }
}