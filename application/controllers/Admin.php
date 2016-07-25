<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Admin
 *
 * Controla o Sistema Administrativo
 *
 * @package      Admin
 * @author       Natan Felles <natanfelles@gmail.com>
 *
 * @property Admin_model $admin_model
 */
class Admin extends CI_Controller {

	/**
	 * Admin constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('email');
		$this->load->helpers(['url', 'string', 'date']);
		$this->load->model('admin_model');
	}

	/**
	 * Página inicial da administração
	 */
	public function index()
	{
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

	/**
	 * Realiza a autenticação de usuários
	 *
	 * @see Admin::index()
	 *
	 * @return array
	 */
	protected function login()
	{
		$status = $this->admin_model->login($this->input->post());

		if ($status === TRUE)
		{
			$this->session->set_userdata('auth', TRUE);
			$user = $this->admin_model->get_profile($this->input->post('username'));
			$this->session->set_userdata($user);
			header('Location: ' . base_url('admin'));
		}

		return $message = array(
			'type'    => 'danger',
			'content' => 'Não foi possível entrar.',
		);
	}

	/**
	 * Remove a autenticação do usuário
	 *
	 * @see Admin::index()
	 */
	protected function logout()
	{
		$this->session->unset_userdata('auth');
		header('Location: ' . base_url('admin'));
	}

	/**
	 * Página de Recuperação de Senhas
	 *
	 * Caminho: /admin/recover-password
	 */
	public function recover_password()
	{
		$data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash(),
		);

		if ($this->input->post('recover', TRUE))
		{
			$data['message'] = $this->try_recover_password($this->input->post());
		}

		$data['title'] = 'Recuperar Senha';
		$this->load->view('dev/templates/head', $data);
		$this->load->view('dev/admin/recover-password', $data);
		$this->load->view('dev/templates/footer', $data);
	}

	/**
	 * Realiza verificações necessárias para a recuperação de senha e envia e-mail para o usuário
	 *
	 * @see Admin::recover_password()
	 *
	 * @param array $user
	 *
	 * @return array
	 */
	protected function try_recover_password($user = array())
	{
		if ($this->session->userdata('recover_password_sends'))
		{
			$r = $this->session->userdata('recover_password_sends');
		}
		else
		{
			$r = 0;
		}
		$this->session->set_userdata('recover_password_sends', ++$r);

		$message = array(
			'type'    => 'success',
			'content' => 'Se seus dados estiverem corretos, foi enviado um link de recuperação para o seu e-mail.',
		);

		if ($r < 5)
		{
			$recover_password_code = $this->admin_model->set_recover_password($user);

			if ($recover_password_code)
			{
				$config['protocol'] = 'smtp';
				$config['smtp_host'] = 'ssl://smtp.gmail.com';
				$config['smtp_port'] = 465;
				$config['smtp_user'] = 'sapiranganoticias@gmail.com';
				$config['smtp_pass'] = 'Senh$$4';
				$config['charset'] = 'utf-8';
				$config['newline'] = "\r\n";
				$config['mailtype'] = 'html';
				$config['wordwrap'] = TRUE;

				$this->email->initialize($config);

				$this->email->from('sapiranganoticias@gmail.com', 'Sapiranga Notícias');
				$this->email->to($user['email']);
				$this->email->subject('Recuperação de Senha');
				$this->email->message("<h1>Olá, {$user['username']}!</h1> <p>Recebemos uma solicitação para recuperar a sua senha.</p> <p>Se não foi você, favor desconsiderar essa mensagem.</p><p>Se quiser modificar sua senha, clique <a 
href='http://sapiranganoticias.tk/admin/new-password/{$recover_password_code}'>nesse link</a> ou copie e cole o endereço abaixo em seu navegador: </p><p>http://sapiranganoticias.tk/admin/new-password/{$recover_password_code}</p>");

				$this->email->send();
			}

			if ($r == 2)
			{
				$message = array(
					'type'    => 'warning',
					'content' => '<strong>Este recurso é limitado.</strong><br> Por favor, verifique seu e-mail.',
				);

				return $message;
			}
		}
		else
		{
			$message = array(
				'type'    => 'danger',
				'content' => '<strong>Você já realizou muitas requisições.</strong><br> Por favor, verifique seu e-mail.',
			);
		}

		return $message;
	}

	/**
	 * Página para modificar a senha do usuário
	 *
	 * Caminho: /admin/new-password
	 */
	public function new_password()
	{
		$code = $this->uri->segment(3);
		if ( ! isset($code))
		{
			header('Location: ' . base_url('admin'));
		}

		$auth_change_password = $this->admin_model->auth_recover_password($code);

		$data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash(),
		);

		$data['title'] = 'Recuperar Senha';
		$this->load->view('dev/templates/head', $data);
		if ($auth_change_password)
		{
			$data['message'] = array(
				'type'    => 'info',
				'content' => 'Guarde sua senha em um local seguro depois de modificá-la.',
			);

			/**
			 * @todo Fazer validação - senhas iguais repeat
			 */
			if ($this->input->post('recover'))
			{
				$user = array(
					'user_username' => $this->input->post('username'),
					'user_password' => $this->input->post('password'),
				);
				$this->admin_model->set_new_password($user);
				header('Location: ' . site_url('admin'));
			}

			$this->load->view('dev/admin/new-password', $data);
		}
		else
		{
			$data['message'] = array(
				'type'    => 'danger',
				'content' => 'Este link de Recuperação de Senha é inválido ou expirou.',
			);
			$this->load->view('dev/admin/new-password', $data);
		}
		$this->load->view('dev/templates/footer', $data);
	}

	public function profile()
	{
		if ( ! $this->session->userdata('auth'))
		{
			header('Location: ' . site_url('admin'));
		}

		$data['csrf'] = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash(),
		);

		$data['user'] = $this->session->get_userdata();

		if ($this->input->post('profile'))
		{
			$profile = $this->input->post();
			$profile['user_id'] = $data['user']['user_id'];
			$this->admin_model->update_profile($profile);
			$user = $this->admin_model->get_profile($this->input->post('username'));
			$this->session->set_userdata($user);

			$data['user'] = $this->session->get_userdata();

			$data['message'] = array(
				'type'    => 'success',
				'content' => 'Perfil atualizado com sucesso.',
			);
		}

		$data['title'] = 'Perfil';
		$this->load->view('dev/templates/head', $data);
		$this->load->view('dev/admin/profile', $data);
		$this->load->view('dev/templates/footer', $data);
	}

}
