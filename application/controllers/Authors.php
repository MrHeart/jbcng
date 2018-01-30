<?php

class Authors extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('adminModel');
        $this->load->helper('url_helper');
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
    }

    public function View($id = NULL){
        $data = array();
        $data["title"] = "View Authors";
        $data["author_books"] = $this->adminModel->getAuthorBooks($id);
        $data["author_activities"] = $this->adminModel->getAuthorActivities($id);
        if(isset($id) && !empty($id) && !is_null($id)){
            $data["authors"] = $this->adminModel->getAuthors($id);
            $this->layout->view('pages/authors_profile', $data);
            return;
        }
        $data["authors"] = $this->adminModel->getAuthors();
        $this->layout->view('pages/authors_view', $data);
    }

    public function Edit($id){
        $author = $this->adminModel->getAuthors($id);
        echo json_encode($author);
    }

    public function Save(){
        $this->form_validation->set_rules('first_name', 'Team', 'trim|required|xss_clean');
        $this->form_validation->set_rules('other_name', 'Team', 'trim|required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Team', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            //@todo handle error message here
        }
        $id = $this->input->post(html_escape('author_id'));
        $first_name = $this->input->post(html_escape('first_name'));
        $other_name = $this->input->post(html_escape('other_name'));
        $last_name = $this->input->post(html_escape('last_name'));
        $email = $this->input->post(html_escape('email'));
        $phone = $this->input->post(html_escape('phone'));

        $data = array(
            'first_name' =>$first_name,
            'other_name' =>$other_name,
            'last_name' =>$last_name,
            'email' =>$email,
            'phone' =>$phone
        );

        $insert = $this->adminModel->update($data, $id);
        echo json_encode(array("status" => TRUE));
    }

    public function Delete($id)
    {
        $this->adminModel->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
}