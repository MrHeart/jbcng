<?php
/**
 * Created by PhpStorm.
 * User: TimonoloGy
 * Date: 10/3/2016
 * Time: 1:25 AM
 */

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('adminModel');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }
    public function Index()
    {
        $data["all_users"] = $this->adminModel->all_users();
        //echo $data['all_users']; exit();
        $this->layout->view('pages/admin_user.php', $data);
    }
    public function fetchUsers()
    {
        $result = array('data' => array());
        $data = $this->adminModel->fetchUserModel();
        foreach($data as $key => $value){

            $button = '
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Action <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a type="button" onclick="editUser('.$value['id'].')" data-toggle="modal" data-target="#editUserModel" >Edit</a></li>
                <li><a type="button" onclick="removeUser('.$value['id'].')" data-toggle="modal" data-target="#removeUserModel" >Remove</a></li>
              </ul>
            </div>
            ';

            $result['data'][$key] = array(
                $value['id'],
                $value['surname'],
                $value['other_names'],
                $value['email'],
                $value['phone'],
                $value['address'],
                $value['state'],
                $value['lga'],
                $button

            );
        }
        echo json_encode($result);

    }
    public function getSelectedUser($id)
    {
        if($id){
            $data = $this->adminModel->fetchUserModel($id);
            echo json_encode($data);
        }
    }
    public function edit($id = null)
    {
        if($id){
            $validator = array('success' => false, 'message' => array());

            $config = array(
                array(
                    'field' => 'edit_surname',
                    'label' => 'SurName',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'edit_other_names',
                    'label' => 'Other Names',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'edit_phone',
                    'label' => 'Phone Number',
                    'rules' => 'trim|required|integer'
                )
            );
            $this->form_validation->set_rules($config);
            $this->form_validation->set_error_delimiters('<p class="text-danger">,</p>');

            if($this->form_validation->run() == true){
                $editUser =  $this->adminModel->edit($id);

                if($editUser === true){
                    $validator['success'] = true;
                    $validator['message'] = "This User is just successfully Updated";
                }else{
                    $validator['success'] = false;
                    $validator['message'] = "Error while Insert this information";
                }

            }else{
                $validator['success'] = false;
                foreach($_POST as $key => $value){
                    $validator['message'][$key] = form_error($key);
                }
            };
            echo json_encode($validator);
        }
    }
    public function createUser()
    {
        //$this->layout->view('pages/manage_users.php');
        $validator = array('success' => false, 'message' => array());

        $config = array(
            array(
                'field' => 'surname',
                'label' => 'SurName',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'other_names',
                'label' => 'Other Names',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'phone',
                'label' => 'Phone Number',
                'rules' => 'trim|required|integer'
            )
        );
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<p class="text-danger">,</p>');

        if($this->form_validation->run() == true){
            $createUser =  $this->adminModel->create();

            if($createUser === true){
                $validator['success'] = true;
                $validator['message'] = "You successfully added this User";
            }else{
                $validator['success'] = false;
                $validator['message'] = "Error while Insert this information";
            }

        }else{
            $validator['success'] = false;
            foreach($_POST as $key => $value){
                $validator['message'][$key] = form_error($key);
            }
        };
        echo json_encode($validator);

    }
    public function ManageUsers()
    {


        /*
        $ok = $_POST;

        $data["all_users"] = $this->adminModel->all_users();
        if($this->input->is_ajax_request()){
            if($this->adminModel->create_users($ok) == true){
                echo "Am good ";
            }else{
                echo "You are not good to go oof";
            }
        }
        */
        $this->layout->view('pages/manage_users.php');


    }
    public function ViewUser($id)
    {
        $id = $id;
        $data['users'] = $this->adminModel->viewUsers($id);

        $this->layout->view('pages/view_users.php', $data);
    }
    public function search()
    {
        if($this->input->is_ajax_request()){
            $value = $this->input->post('search');
            $result = $this->adminModel->search($value);
            if(count($result) > 0){
                foreach($result as $data ){
                    $arr_result = $data->surname;
                    $arr_names = $data->other_names;
                    $arr_email = $data->email;
                    echo $arr_result;
                    echo $arr_result;
                }
            }else{
                // do something
            }
        }

    }
    public function delete($id)
    {
        $id = $id;
        $this->adminModel->delete_user($id);
        echo ("<script> window.alert('Succesfully Deleted This User')
        window.location.href='localhost:8080/Users/ManageUsers';
    </script>");
    }
}