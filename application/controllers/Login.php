<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('user_model');
	}
	// 1. login page show
	public function signin()
	{
		$this->load->view('include/login');
	}
	// 2. login form sumit
	public function login()
	{
		$this->form_validation->set_rules('email','email',"required|valid_email");
		$this->form_validation->set_rules('password','password',"required|min_length[4]");
		if($this->form_validation->run()==FALSE)
		{
		$this->load->view('include/login');
		}else
		{
			 $email = $this->input->post('email');
             $password = $this->input->post('password');
             $this->load->model('User_model');
             $user = $this->User_model->getuser_login($email, $password);
             $this->session->set_userdata('name', $user->name);
             $this->session->set_userdata('id', $user->id);
              if ($user && $user->user_type == "1") {
               $this->session->set_flashdata('success', 'Login success. Welcome '.$user->name);
                redirect('home'); 
             } 
             elseif ($user && $user->user_type == "0") {
               $this->session->set_flashdata('success', 'Login success. Welcome '.$user->name);
                redirect('user-details'); 
             } 
             else {
               $this->session->set_flashdata('error', 'Invalid Email or Password!');
               redirect('login');            
              }

		}
	}
		// 3. reg  form show
	public function signup()
	{
		$this->load->view('include/register');
	}
	// 4. reg  form submit
	public function register()
	{
		$this->form_validation->set_rules('name','name',"required");
		$this->form_validation->set_rules('email','email',"required|valid_email");
		$this->form_validation->set_rules('password','password',"required|min_length[4]");
		if($this->form_validation->run()==FALSE)
		{
		$this->load->view('include/register');
		}else
		{
			$data = [
        'name'  => $this->input->post('name'),
        'email' => $this->input->post('email'),
        'password' => $this->input->post('password'),
        'date'  => date("Y-m-d H:i:s"),
         'user_type'  => "1"
                   ];
          $this->load->model('User_model');
          $this->User_model->insert_user($data);     
          redirect('login');

		}

	}
	// 5. product add card user login check
	public function add()
   {
    if (!$this->session->userdata('id')) {
        echo json_encode([
            "status" => "error",
            "message" => "Please login first!"
        ]);
        return;
    }
    $id = $this->input->post('id');
    $this->load->model('User_model');
    $product = $this->User_model->get_product_by_id($id);
    if ($product) {
        $cart = $this->session->userdata('cart') ?? [];
        $cart[$id] = $product;
        $this->session->set_userdata('cart', $cart);

        echo json_encode([
            "status" => "success",
            "message" => $product->product_name . " added to cart!"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Product not found!"
        ]);
     }
   }

	
}
