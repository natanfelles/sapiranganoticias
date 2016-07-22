<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Admin_model
 *
 * @package      Admin
 * @author       Natan Felles <natanfelles@gmail.com>
 *
 */
class Admin_model extends CI_Model {

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

}
