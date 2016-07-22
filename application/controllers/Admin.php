<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Admin
 *
 * @package      Admin
 * @author       Natan Felles <natanfelles@gmail.com>
 */
class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	public function index()
	{
		$this->load->view('templates/head');
		if ($this->session->userdata('auth'))
		{
			$this->load->view('admin/dashboard');
		}
		else
		{
			$this->load->view('admin/login');
		}
		$this->load->view('templates/footer');
	}

}