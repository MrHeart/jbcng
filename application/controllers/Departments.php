<?php

/**
 * Created by PhpStorm.
 * User: Mr Heart
 * Date: 10/2/2016
 * Time: 9:40 PM
 */
class Departments extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('adminModel');
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
    }

    public function Create(){
        if(isset($_POST["submit"])){
            $name = htmlspecialchars(trim($_POST["name"]));
            if(!empty($name)){
                $this->adminModel->CreateDepartment($name);
                $data["message"] = "Department created successfully!";
            }

        }
        $data["title"] = "Create Departments";
        $this->layout->view('pages/departments_create', $data);

    }

    public function Manage(){
        $data["departments"] = $this->adminModel->GetDepartments();
        $data["title"] = "Create Departments";
        $this->layout->view('pages/departments_manage', $data);

    }

    public function Edit($id){
        $department = $this->adminModel->GetDepartments($id);
        echo json_encode($department);
    }

}