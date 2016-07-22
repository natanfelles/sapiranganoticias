<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Admin
 *
 * @package      Admin
 * @author       Natan Felles <natanfelles@gmail.com>
 *
 * @property Admin_model $admin_model
 */
class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helpers(['url']);
		$this->load->model('admin_model');
	}

	/**
	 * Página inicial da administração
	 *
	 */
	public function index()
	{
		$data['message'] = array();

		$data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash(),
		);

		if ($this->session->userdata('auth'))
		{
			if ($this->input->post('logout', TRUE))
			{
				$this->logout();
			}
			$data['title'] = 'Painel Administrativo';
			$this->load->view('dev/templates/head', $data);
			$this->load->view('dev/admin/dashboard', $data);
		}
		else
		{
			if ($this->input->post('login', TRUE))
			{
				$data['message'] = $this->login();
			}
			$data['title'] = 'Login';
			$this->load->view('dev/templates/head', $data);
			$this->load->view('dev/admin/login', $data);
		}
		$this->load->view('dev/templates/footer');
	}

	protected function login()
	{
		$status = $this->admin_model->login($this->input->post());

		if ($status === TRUE)
		{
			$this->session->set_userdata('auth', TRUE);
			header('Location: ' . base_url('admin'));
		}

		return $message = array(
			'type'    => 'danger',
			'content' => 'Não foi possível entrar.',
		);
	}

	protected function logout()
	{
		$this->session->unset_userdata('auth');
		header('Location: ' . base_url('admin'));
	}

}