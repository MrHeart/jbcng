<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	function __constuct(){
		parent::__constuct();
	}
  
	public function index()
	{
		$data['title'] = "African Stock";		
		$data['include'] = 'home_view';
		$this->load->vars($data);
		$this->load->view('template');
	}
}
