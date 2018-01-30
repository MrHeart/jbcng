<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clearlog extends CI_Controller {
	function __constuct(){
		parent::__constuct();
	}
  
	public function index()
	{
		$this->load->helper('form');
		$data['title'] = "African Stock - Clear Log Entries";
		$data['include'] = 'clearlog_view';
		$this->load->vars($data);
		$this->load->view('template');
	}
	
}
