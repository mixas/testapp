<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hello extends CI_Controller {

	function index()
	{
		$this->load->view('hello/index');
	}
}