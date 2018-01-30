<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Changepwd extends CI_Controller {
	function __constuct(){
		parent::__constuct();
	}
  
	public function index()
	{
		$this->load->helper('form');
		$data['title'] = "African Stock - Change User Password";
		$data['include'] = 'changepwd_view';
		$this->load->vars($data);
		$this->load->view('logintemplate');
	}
}
