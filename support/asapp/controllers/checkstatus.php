<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checkstatus extends CI_Controller {
	function __constuct(){
		parent::__constuct();
	}
  
	public function index()
	{
		$this->load->helper('form');
		$data['title'] = "African Stock - Check Payment Status";
		$data['include'] = 'checkstatus_view';
		$this->load->vars($data);
		$this->load->view('template');
	}
	
}
