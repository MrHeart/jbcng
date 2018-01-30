<?php

/**
 * Created by PhpStorm.
 * User: Mr Heart
 * Date: 7/26/2016
 * Time: 1:33 PM
 */
class Home extends CI_Controller{
    public $lagos_users;
    public $ogun_users;
    public $osun_users;
    public $ekiti_users;
    public $oyo_users;

    public function __construct(){
        parent::__construct();
        $this->load->model('adminModel', 'adminData');
        $this->load->helper('url_helper');
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->lagos_users = $this->adminData->usersLagos();
        $this->ogun_users = $this->adminData->usersOgun();
        $this->osun_users = $this->adminData->usersOsun();
        $this->ekiti_users = $this->adminData->usersEkiti();
        $this->oyo_users = $this->adminData->usersOyo();

    }

    public function index(){
        $data = array();
        $data["title"] = "Dashboard";
        $data["numUser"] = $this->adminData->userTotal();
        $data["subTotal"] = $this->adminData->subjectTotal();
        $data["queTotal"] = $this->adminData->questionTotal();
        $data["adminTotal"]  = $this->adminData->adminTotal();
        $data["usersLagos"] = $this->lagos_users;
        $data["usersOgun"] = $this->ogun_users;
        $data["usersOsun"] = $this->osun_users;
        $data["usersEkiti"] = $this->ekiti_users;
        $data["usersOyo"] = $this->oyo_users;
        //$data["numAdmin"] = $this->adminModel->adminTotal();
        //$data["numActivity"] = $this->adminModel->activityTotal();
        $this->layout->view('pages/index', $data);
    }

    public function report(){
        $data = array();
        $data["title"] = "Report";
        $data["usersLagos"] = $this->adminData->usersLagos();
        $data["usersOgun"] = $this->adminData->usersOgun();
        $data["usersOsun"] = $this->adminData->usersOsun();
        $data["usersEkiti"]  = $this->adminData->usersEkiti();
        $data["usersOyo"] = $this->adminData->usersOyo();

        $this->layout->view('pages/report', $data);
    }

        //lIST ALL REGISTERED STUDENTS
        public function list_students()
        {
            $list = $this->adminData->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $student) {
                $no++;
                $row = array();
                $row[] = $student->surname;
                $row[] = $student->other_names;
                $row[] = $student->email;
                $row[] = $student->phone;
                $row[] = $student->address;
                $row[] = $student->state;

                //add html for action
                //$row[] = '<a class="btn btn-sm btn-success" href="javascript:void()" title="Edit" onclick="view_student('."'".$student->id."'".')"><i class="fa fa-eye"></i></a>';

                $data[] = $row;
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->adminData->count_all(),
                "recordsFiltered" => $this->adminData->count_filtered(),
                "data" => $data,
            );
            //output to json format
            echo json_encode($output);

        }

    public function view_student($id)
    {
        $data = $this->adminData->get_by_id($id);
        echo json_encode($data);
    }

    public  function sendEmail(){
         if(isset($_POST['sendmail'])){

             $to =  $this->input->post('mailto');
             $subject = $this->input->post('subject');
             $message =  $this->input->post('message');
             $headers = 'From: support@ojaja.com' . "\r\n" .
                 'Reply-To: upport@ojaja.com' . "\r\n" .
                 'X-Mailer: PHP/' . phpversion();

             mail($to, $subject, $message, $headers);

             $data = array();
             $data["message"] = "Message Successful Sent!";



         }
        $this->layout->view('pages/report', $data);

    }




}