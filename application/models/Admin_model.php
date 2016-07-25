<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Admin_model
 *
 * Model do Sistema Administrativo
 *
 * @package      Admin
 * @author       Natan Felles <natanfelles@gmail.com>
 *
 */
class Admin_model extends CI_Model {

	/**
	 * Admin_model constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('encryption');
	}

	/**
	 * Realiza a autenticação do usuário
	 *
	 * @param array $user username|password
	 *
	 * @return bool
	 */
	public function login($user = array())
	{
		$data = $this->db->select('user_username, user_password')
		                 ->where('user_username', $user['username'])
		                 ->get('users')
		                 ->row_array();

		if (isset($data['user_username']))
		{
			$hash = $this->encryption->decrypt($data['user_password']);

			if (password_verify($user['password'], $hash))
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Retorna o Código de Recuperação de Senha se o usuário for válido
	 *
	 * @see Admin::try_recover_password()
	 *
	 * @param array $user username|email
	 *
	 * @return bool|string
	 */
	public function set_recover_password($user = array())
	{
		$data = $this->db->select('user_id')
		                 ->where('user_username', $user['username'])
		                 ->where('user_email', $user['email'])
		                 ->get('users')
		                 ->row_array();

		if (isset($data['user_id']))
		{
			$verify = $this->db->select('user_id')
			                   ->where('user_id', $data['user_id'])
			                   ->get('recover_passwords')
			                   ->row_array();

			$data['recover_password_code'] = random_string('alnum', random_int(16, 64));
			$data['recover_password_timestamp'] = date('Y-m-d H:i:s', time());

			if (count($verify) > 0)
			{
				$this->db->where('user_id', $user['user_id'])
				         ->update('recover_passwords', $data);
			}
			else
			{
				$this->db->insert('recover_passwords', $data);
			}

			return $data['recover_password_code'];
		}

		return FALSE;
	}

	/**
	 * Autentifica se o Código de Recuperação de Senha é válido
	 *
	 * @see Admin::new_password()
	 *
	 * @param string $code Código de Recuperação de Senha
	 *
	 * @return bool
	 */
	public function auth_recover_password($code = '')
	{
		$q = $this->db->select('recover_password_timestamp')
		              ->where('recover_password_code', $code)
		              ->get('recover_passwords')
		              ->row_array();

		if (isset($q['recover_password_timestamp']))
		{
			// Período de recuperação é de 1 dia
			$period = mysql_to_unix($q['recover_password_timestamp']) + 86400;

			if ($period > time())
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	public function set_new_password($data = array())
	{
		$password = password_hash($data['user_password'], PASSWORD_DEFAULT);
		$data['user_password'] = $this->encryption->encrypt($password);

		return $this->db->set('user_password', $data['user_password'])
		                ->where('user_username', $data['user_username'])
		                ->update('users');
	}

}
