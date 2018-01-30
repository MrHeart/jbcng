<?php

/**
 * Created by PhpStorm.
 * User: Mr Heart
 * Date: 10/2/2016
 * Time: 9:15 PM
 */
class Subjects extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('adminModel');
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
    }

    public function Create(){
        $data["departments"] = $this->adminModel->GetDepartments();
        if(isset($_POST["submit"])){
            $name = htmlspecialchars(trim($_POST["name"]));
            $department = htmlspecialchars(trim($_POST["department"]));
            if(!empty($name) && !empty($department)){
                $this->adminModel->CreateSubject($name, $department);
                $data["message"] = "Subject created successfully!";
            }

        }
        $data["title"] = "Create Subjects";
        $this->layout->view('pages/subjects_create', $data);

    }
}