<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct ()
	{
		parent::__construct();
		$this->load->model('database_model');
	}

	public function index()
	{
		
		$passdata['table'] = $this->database_model->get_claim(true);
		$data = array('page_title' => 'Homepage - VigoRISE');
		$this->parser->parse('template/header', $data);
		$this->load->view('cipreg/index',$passdata);
		$this->load->view('template/footer');
	}
	public function claim()
	{
		// Form Validation
		$validity = $this->form_validation->run();
		$passdata['valid_err'] = validation_errors();

		$passdata['activity_name'] = $this->database_model->get_activity_name();
		$passdata['school_name'] = $this->database_model->get_school_name();
		$passdata['user_name'] = $this->database_model->get_user_name();
		$data = array('page_title' => 'Claim CIP Hours - VigoRISE');
		$this->parser->parse('template/header', $data);
		if($validity){
			$this->database_model->add_claim($_POST);
			$this->load->view('cipreg/claim_success');
		}else{
			$this->load->view('cipreg/claim',$passdata);
		}
		$this->load->view('template/footer');

	}
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */