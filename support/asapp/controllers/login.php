<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	function __constuct(){
		parent::__constuct();
	}
  
	public function index()
	{
		$data['title'] = "African Stock";
		$data['include'] = 'login_view';
		$this->load->vars($data);
		$this->load->view('logintemplate');
	}
}
