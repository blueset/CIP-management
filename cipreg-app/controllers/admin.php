<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
  	{
    	parent::__construct();
    	$this->load->model('database_model');
    	$this->load->model('user_model');
    	$this->load->helper('url');
	    //if($this->user_model->logged_in() !== TRUE){redirect('login?target='.uri_string());}
  	}
	function login(){

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|callback_username_check');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|md5|callback_password_check');
        $this->_username = $this->input->post('username');                //用户名
        
        
        $data = array('page_title' => 'Login - VigoRISE');
		$this->parser->parse('template/header', $data);
		if($this->user_model->logged_in() === TRUE){
       		$data['message'] = "Redirect to Dashboard...";
            $data['path'] = "admin/approval";
            $this->load->view('template/redirect',$data);
            return;
       	};
		if ($this->form_validation->run()){
        	$userinfo=$this->user_model->get_by_username($this->_username);
            $this->user_model->login($userinfo);
            $data['message'] = "Login successful.";
            $data['path'] = "admin/approval";
            $this->load->view('template/redirect',$data);
        }else{
        	$this->load->view('admin/login');
        }
        
        $this->load->view('template/footer');
    }

    function approval($page=1)
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$user_id = (int) $this->session->userdata('user_id');
    	$config['base_url'] = site_url('admin/image');
    	$config['total_rows'] = $this->database_model->get_claim_number();
    	$config['per_page'] = 50; 
    	$config['uri_segment'] = 3; 

    	$this->pagination->initialize($config); 
    	
      	if($page==1){
        	$offset=0;
	    }else{
	        $offset=$config['per_page']*($page-1);
      	}
      	$pushdata['claim'] = $this->database_model->get_claim(true,$config['per_page'],$offset);
      	$pushdata['activity_name'] = $this->database_model->get_activity_name();
		$pushdata['school_name'] = $this->database_model->get_school_name();
		$pushdata['user_name'] = $this->database_model->get_user_name();
      	$data = array('page_title' => 'Approval - Admin - VigoRISE',
    		'extra_header' => '<div class="container">');
		$this->parser->parse('template/header', $data);
		$this->load->view('template/admin_sidebar');
		$this->load->view('admin/approval',$pushdata);
		$this->load->view('template/footer',array('extra_footer'=>'</div>'));
    }
    function logout(){
        if ($this->user_model->logout() == TRUE){
        	$data = array('page_title' => 'Log out - Admin - VigoRISE');
			$this->parser->parse('template/header', $data);
        	$pushdata['message']='Log out successfully!';
        	$pushdata['path']="";
            $this->load->view('template/redirect',$pushdata);
            $this->load->view('template/footer');
        }else{
        	 redirect('/', 'refresh');
        }
    }
    function username_check($username){
        if ($this->user_model->get_by_username($username)){
            return TRUE;
        }else{
            $this->form_validation->set_message('username_check', 'User name not exist.');
            return FALSE;
        }
    }
    function password_check($password) {
        if ($this->user_model->password_check($this->_username, $password)){
            return TRUE;
        }else{
            $this->form_validation->set_message('password_check', 'Incorrect username or paswsword.');
            return FALSE;
        }
    }
    function username_exists($username){
        if ($this->user_model->get_by_username($username)){
            $this->form_validation->set_message('username_exists', 'User name is not available.');
            return FALSE;
        }
        return TRUE;
    }
    public function approve_claim($claim_id)
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$this->database_model->approve_claim($claim_id);
    }
    public function report_view()
    {
    	$this->load->library('table');
    	$tmpl = array (
                    'table_open'          => '<table class="table table-striped table-bordered table-condensed">',

                    'heading_row_start'   => '<tr>',
                    'heading_row_end'     => '</tr>',
                    'heading_cell_start'  => '<th>',
                    'heading_cell_end'    => '</th>',

                    'row_start'           => '<tr>',
                    'row_end'             => '</tr>',
                    'cell_start'          => '<td>',
                    'cell_end'            => '</td>',

                    'row_alt_start'       => '<tr>',
                    'row_alt_end'         => '</tr>',
                    'cell_alt_start'      => '<td>',
                    'cell_alt_end'        => '</td>',

                    'table_close'         => '</table>'
              );

		$this->table->set_template($tmpl);
    	
    	$query = $this->database_model->generate_report();
    	$pushdata['table'] = $this->table->generate($query);
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$data = array('page_title' => 'View Report - Admin - VigoRISE',);
		$this->parser->parse('template/header', $data);
		$this->load->view('template/admin_sidebar');
		$this->load->view('admin/report',$pushdata);
		$this->load->view('template/footer');
    }
    public function event()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$data = array('page_title' => 'Manage event - Admin - VigoRISE',
    		'extra_header' => '<div class="container">');
		$this->parser->parse('template/header', $data);
		$this->load->view('template/admin_sidebar');
		$pushdata['events'] = $this->database_model->get_activity_name();
		$this->load->view('admin/event',$pushdata);
		$this->load->view('template/footer',array('extra_footer'=>'</div>'));
    }
    public function user()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$pushdata['events'] = $this->database_model->get_user_name();
    	$data = array('page_title' => 'Manage user - Admin - VigoRISE',
    		'extra_header' => '<div class="container">');
		$this->parser->parse('template/header', $data);
		$this->load->view('template/admin_sidebar');
		$this->load->view('admin/user',$pushdata);
		$this->load->view('template/footer',array('extra_footer'=>'</div>'));
    }
    public function subcom()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$pushdata['subcom'] = $this->database_model->get_subcom();
    	$data = array('page_title' => 'Manage Subcom Link - Admin - VigoRISE',
    		'extra_header' => '<div class="container">');
		$this->parser->parse('template/header', $data);
		$this->load->view('template/admin_sidebar');
		$this->load->view('admin/subcom',$pushdata);
		$this->load->view('template/footer',array('extra_footer'=>'</div>'));
    }
    public function new_event()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	if($_POST['event_name'] != ""){
    		$this->database_model->new_event($_POST['event_name']);
    	}
    }
    public function edit_event()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	if($_POST['event_name'] != "" && $_POST['event_id'] != ""){$this->database_model->edit_event($_POST['event_id'],$_POST['event_name']);}
    }
    public function new_user()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$rule=array(                    //用户注册表单的规则
            array(
                'field'=>'username',
                'label'=>'User name',
                'rules'=>'trim|required|xss_clean|callback_username_exists'
            ),
            array(
                'field'=>'password',
                'label'=>'Paassword',
                'rules'=>'trim|required|min_length[4]|max_length[12]|xss_clean'
            ),
            array(
                'field'=>'display_name',
                'label'=>'Display Name',
                'rules'=>'trim|required|xss_clean'
            )
        );
    	$this->form_validation->set_rules($rule);
    	$callback = $this->input->get('callback') == "" ? "callback" : $this->input->get('callback');
    	if ($this->form_validation->run()) {
            $username = $this->input->post('username');
            $password = md5($this->input->post('password'));
            $display_name = $this->input->post('display_name');
            $this->user_model->add_user($username, $password, $display_name);
            echo $callback.'("OK")';
        }else{
        	echo $callback.'("'.preg_replace( "/\r|\n/", "", validation_errors() ).'")';
        }
    }
    public function edit_user()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$rule=array(                    //用户注册表单的规则
    		array(
                'field'=>'id',
                'label'=>'ID',
                'rules'=>'trim|required|integer|xss_clean'
            ),
            array(
                'field'=>'username',
                'label'=>'Username',
                'rules'=>'trim|required|xss_clean'
            ),
            array(
                'field'=>'password',
                'label'=>'Paassword',
                'rules'=>'trim||min_length[4]|max_length[12]|xss_clean'
            ),
            array(
                'field'=>'display_name',
                'label'=>'Display Name',
                'rules'=>'trim|required|xss_clean'
            )
        );
    	$this->form_validation->set_rules($rule);
    	$callback = $this->input->get('callback') == "" ? "callback" : $this->input->get('callback');
    	if ($this->form_validation->run()) {
    		$id = $this->input->post('id');
            $username = $this->input->post('username');
            $password = $this->input->post('password') == "" ? "" : md5($this->input->post('password'));
            $display_name = $this->input->post('display_name');
            $this->user_model->edit_user($id, $username, $password, $display_name);
            echo $callback.'("OK")';
        }else{
        	echo $callback.'("'.preg_replace( "/\r|\n/", "", validation_errors() ).'")';
        }
    }
    public function get_user()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$callback = $this->input->get('callback') == "" ? "callback" : $this->input->get('callback');
    	$id = $this->input->post('id');
    	if($id != ""){echo $callback.'('.json_encode($this->user_model->get_by_id($id)).')';}
    	else{echo $callback.'("Failed")';}
    	$callback = $this->input->get('callback') == "" ? "callback" : $this->input->get('callback');
    	
    }
    public function new_subcom()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$rule=array(                    //用户注册表单的规则
            array(
                'field'=>'nric',
                'label'=>'NRIC',
                'rules'=>'trim|is_unique[subcom.nric]'
            )
        );
    	$this->form_validation->set_rules($rule);
    	$callback = $this->input->get('callback') == "" ? "callback" : $this->input->get('callback');
    	if ($this->form_validation->run()) {
            $nric = $this->input->post('nric');
            $subcom = $this->input->post('subcom');
            $this->database_model->new_subcom($nric, $subcom);
            echo $callback.'("OK")';
        }else{
        	echo $callback.'("'.preg_replace( "/\r|\n/", "", validation_errors() ).'")';
        }
    }
    public function edit_subcom()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$rule=array(                    //用户注册表单的规则
    		array(
                'field'=>'id',
                'label'=>'ID',
                'rules'=>'trim|required|integer|xss_clean'
            ),
            array(
                'field'=>'nric',
                'label'=>'NRIC',
                'rules'=>'trim|is_unique[subcom.nric]'
            )
        );
    	$this->form_validation->set_rules($rule);
    	$callback = $this->input->get('callback') == "" ? "callback" : $this->input->get('callback');
    	if ($this->form_validation->run()) {
    		$id = $this->input->post('id');
            $nric = $this->input->post('nric');
            $subcom = $this->input->post('subcom');
            $this->database_model->edit_subcom($id, $nric, $subcom);
            echo $callback.'("OK")';
        }else{
        	echo $callback.'("'.preg_replace( "/\r|\n/", "", validation_errors() ).'")';
        }
    }
    public function delete_subcom()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$rule=array(                    //用户注册表单的规则
    		array(
                'field'=>'id',
                'label'=>'ID',
                'rules'=>'trim|required|integer|xss_clean'
            )
        );
    	$this->form_validation->set_rules($rule);
    	$callback = $this->input->get('callback') == "" ? "callback" : $this->input->get('callback');
    	if ($this->form_validation->run()) {
    		$id = $this->input->post('id');
    		$this->database_model->delete_subcom($id);
            echo $callback.'("OK")';
        }else{
        	echo $callback.'("'.preg_replace( "/\r|\n/", "", validation_errors() ).'")';
        }
    }
    public function edit_claim()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$callback = $this->input->get('callback') == "" ? "callback" : $this->input->get('callback');
    	if ($this->form_validation->run()) {
    		$id = $this->input->post('id');
    		$name = $this->input->post('name');
            $nric = $this->input->post('nric');
            $school_id = $this->input->post('school_id');
            $class = $this->input->post('class');
            $event_id = $this->input->post('event_id');
            $hours = $this->input->post('hours');
            $in_charge = $this->input->post('in_charge');
            $remarks = $this->input->post('remarks');

            $this->database_model->edit_claim($id,$name,$nric,$school_id,$class,$event_id,$hours,$remarks,$in_charge);
            echo $callback.'("OK")';
        }else{
        	echo $callback.'("'.preg_replace( "/\r|\n/", "", validation_errors() ).'")';
        }
    }
    public function delete_claim()
    {
    	if($this->user_model->logged_in() !== TRUE){redirect('admin/login');return;};
    	$rule=array(                    //用户注册表单的规则
    		array(
                'field'=>'id',
                'label'=>'ID',
                'rules'=>'trim|required|integer|xss_clean'
            )
        );
    	$this->form_validation->set_rules($rule);
    	$callback = $this->input->get('callback') == "" ? "callback" : $this->input->get('callback');
    	if ($this->form_validation->run()) {
    		$id = $this->input->post('id');
    		$this->database_model->delete_claim($id);
            echo $callback.'("OK")';
        }else{
        	echo $callback.'("'.preg_replace( "/\r|\n/", "", validation_errors() ).'")';
        }
    }


}