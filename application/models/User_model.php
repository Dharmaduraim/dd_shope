<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_model
{
	 public function __construct()
    {
        parent::__construct();
        $this->load->database();  
    }
	public function insert_user($data)
    {
      return $this->db->insert('user_register', $data);
    }

    public function getuser_login($email, $password)
    {
      $this->db->where('email', $email);
      $this->db->where('password', $password);
      $query = $this->db->get('user_register');
      return $query->row(); 
    }
    public function get_user()
    {
      $query = $this->db->get('user_register');
      return $query->result(); 
    }

    public function get_product()
    {
      $query = $this->db->get('products');
      return $query->result(); 
    }

    public function product_delete($id)
    {
    	return $this->db->delete('products',["id"=>$id]);
    }
    public function get_by_category()
    {
    	$query = $this->db->get('categories');
      return $query->result(); 
    
    }

    public function insert_product($data)
    {
    return $this->db->insert('products', $data);
    }

    public function get_product_by_id($id)
    {
        return $this->db->get_where('products', ['id' => $id])->row();
    }




}

?>