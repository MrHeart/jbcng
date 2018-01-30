<?php

/**
 * Created by PhpStorm.
 * User: Mr Heart
 * Date: 10/3/2016
 * Time: 2:58 PM
 */
class Questions extends CI_Controller
{
    public $subjects;
    public function __construct(){
        parent::__construct();
        $this->load->model('adminModel');
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->subjects = $this->adminModel->GetSubjects();
    }

    public function Create(){
        if(isset($_POST["submit"])){
            $question = htmlspecialchars(trim($_POST["question"]));
            $A = htmlspecialchars(trim($_POST["a"]));
            $B = htmlspecialchars(trim($_POST["b"]));
            $C = htmlspecialchars(trim($_POST["c"]));
            $D = htmlspecialchars(trim($_POST["d"]));
            $answer = htmlspecialchars(trim($_POST["answer"]));
            $subject = htmlspecialchars(trim($_POST["subject"]));
            if(!empty($question) && !empty($A) && !empty($B) && !empty($C) && !empty($D) && !empty($answer) && !empty($subject)){
                $this->adminModel->CreateQuestion($question, $A, $B, $C, $D, $answer, $subject);
                $data["message"] = "Question created successfully!";
            }

        }
        $data["subjects"] = $this->subjects;
        $data["title"] = "Add Questions";
        $this->layout->view('pages/questions_create', $data);

    }


}