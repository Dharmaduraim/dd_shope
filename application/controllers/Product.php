<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('user_model');
	}
    // 1.admin login view page and user list show
	public function admin()
	{
		if (!$this->session->userdata('id')) {
            redirect('login');
        }
		 $this->load->model('User_model');
		 $data['users']=$this->User_model->get_user();
		$this->load->view('include/admin_page',$data);
	}
	// 2.admin  view product_list page
	public function product_list()
	{
		if (!$this->session->userdata('id')) {
            redirect('login');
        }
		 $this->load->model('User_model');
		 $data['products']=$this->User_model->get_product();
		 $this->load->view('include/product_list',$data);
	}
	// 3.admin  view product_delete 
	public function delete()
	{
		if (!$this->session->userdata('id')) {
            redirect('login');
        }
          $id = $this->uri->segment(3);  
          $this->load->model('User_model');
          $this->User_model->product_delete($id);
          $this->session->set_flashdata('danger', 'Product Delete successfully!');
          redirect('products');
	}

	// 4.admin product_add 
	public function product_add()
    {
    	if (!$this->session->userdata('id')) {
            redirect('login');
        }
    $this->load->model('User_model');

    $data = [
        'product_name'       => $this->input->post('product_name'),
        'product_category'   => $this->input->post('product_category'),
        'product_price'      => $this->input->post('product_price'),
        'product_descryption'=> $this->input->post('product_descryption'),
         'product_date'=> date("y-m-d")
    ];

    // upload image
    if (!empty($_FILES['product_image']['name'])) {
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name']     = time() . '_' . $_FILES['product_image']['name'];

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('product_image')) {
            $uploadData = $this->upload->data();
            $data['product_image'] = $uploadData['file_name'];
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('products');
        }
    }
    $this->User_model->insert_product($data);
    $this->session->set_flashdata('success', 'Product added successfully!');
    redirect('products');
}
	// 5.admin product_update 
    public function update()
    {
     if (!$this->session->userdata('id')) {
            redirect('login');
        }	
    $id       = $this->input->post('id');
    $name     = $this->input->post('product_name');
    $category = $this->input->post('product_category');
    $price    = $this->input->post('product_price');
    $desc     = $this->input->post('product_descryption');

    if (!empty($_FILES['product_image']['name'])) {
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 2048;
        
        $this->load->library('upload', $config);

        if ($this->upload->do_upload('product_image')) {
            $uploadData = $this->upload->data();
            $image = $uploadData['file_name'];
        } else {
            $image = $this->input->post('old_image'); // keep old if upload fails
        }
    } else {
        $image = $this->input->post('old_image'); // keep old if not uploaded
    }
    $data = [
        'product_name'       => $name,
        'product_category'   => $category,
        'product_price'      => $price,
        'product_descryption'=> $desc,
        'product_image'      => $image,
    ];
    $this->db->where('id', $id);
    $this->db->update('products', $data);
    redirect('products');
  }
	// 6.product_category  show form
	public function get_products_by_category()
   {
    $this->load->model('User_model');
    $categories = $this->User_model->get_by_category();
    foreach ($categories as $cat) {
            echo "<option value='{$cat->category_name}'>{$cat->category_name}</option>";
        }
   }
	// 6.product_list show home page 
   public function product_listhome()
	{
		 $this->load->model('User_model');
		 $data['products']=$this->User_model->get_product();
		 $this->load->view('home',$data);
	}
	// 6.admin logout function 
	public function logout()
   {
    $this->session->sess_destroy();
    redirect('');
    }


}
?>