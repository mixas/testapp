<?php
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_user extends CI_Model{            
    var $add_rules = array(
               array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'email',
                     'label'   => 'Password',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'password',
                     'label'   => 'Password',
                     'rules'   => 'required'
                  )
               /*array(
                     'field'   => 'email',
                     'label'   => 'Password',
                     'rules'   => 'required'
                  )*/
            );
    var $edit_rules = array ();
            
function mdl_user(){
        parent::__construct();
    }

function getlist(){
    $query = $this->db->get('users');
    $res = $query->result_array();
    return $res;
}
    
function add(){
    $this->form_validation->set_rules($this->add_rules);
    if ($this->form_validation->run()){
        $data = array();
        foreach ($this->add_rules as $each){
            $field = $each['field'];
            $data[$field] = $this->input->post($field);
        }
        $this->db->insert('users',$data);
        return $this->db->insert_id();
    }
    else{
        return false;
    }
}

function update($id){
    $this->form_validation->set_rules($this->add_rules);
    if ($this->form_validation->run()){
        $object = array();
        foreach ($this->add_rules as $each){
            $field = $each['field'];
            $object[$field] = $this->input->post($field);
        }
        $this->db->where('id',$id);
        $this->db->update('users',$object);
        return $this->db->insert_id();
    }
    else{
        return false;
    }
}

function delete($id){
    $this->db->where('id',$id);
    $this->db->delete('users');
}

function show($id){
    $this->db->where('id', $id);
    $query = $this->db->get('users');
    return $query->row_array();
}
    
}

?>