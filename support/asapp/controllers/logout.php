<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {
	function __constuct(){
		parent::__constuct();
	}
  
	public function index()
	{
		$this->load->helper('form');
		$data['title'] = "African Stock - Log Out";
		$data['include'] = 'logout_view';
		$this->load->vars($data);
		$this->load->view('template');
	}
	
}
