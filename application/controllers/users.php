<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users  extends CI_Controller {
    
    function users (){
        parent::__construct();
        $this->load->model('mdl_user');
    }

	function index(){
        $this->load->view('partials/header');
		$this->load->view('users/index');
        $this->load->view('partials/footer');
	}
    
    function add(){
        if ($this->mdl_user->add() !== false){
			redirect('index.php/users');
		}
		else {
			$this->load->view('users/add');
		}
    }
    
    function edit($id){
        $this->db->where('id',$id);
        $request = $this->db->get('users');
        $object = $request->row_array();
        if ($this->mdl_user->update($id)!== false){
			redirect('index.php/users');
		}
		else {
			$this->load->view('users/edit',$object);
		}
    }
    
    function delete($id){
        $this->mdl_user->delete($id);
        redirect('index.php/users');
    }
    
    function show($id){
        $user = $this->mdl_user->show($id);
        $this->load->view('users/show', $user);
    }
}