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
		/**
		 * @todo Selecionar username e password quando houver o username. A senha será descriptografada mais abaixo.
		 */
		$data = $this->db->select('user_id')
		                 ->where('user_username', $user['username'])
		                 ->where('user_password', $user['password'])
		                 ->get('users')
		                 ->row_array();
		/**
		 * @todo Otimizar autenticação! Descriptografar a senha e ver se ela é identica a do $user['password'].
		 */
		if (isset($data['user_id']))
		{
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Retorna o Código de Recuperação de Senha se o usuário for válido
	 *
	 * @param array $user username|email
	 *
	 * @return bool|string
	 */
	public function set_recover_password($user = array())
	{
		$user = $this->db->select('user_id')
		                 ->where('user_username', $user['username'])
		                 ->where('user_email', $user['email'])
		                 ->get('users')
		                 ->row_array();

		if (isset($user['user_id']))
		{
			$verify = $this->db->select('user_id')
			                   ->where('user_id', $user['user_id'])
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
	 * @param string $code
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

}
