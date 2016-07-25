<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package      Admin
 * @author       Natan Felles <natanfelles@gmail.com>
 *
 * @var array $user Dados do Usuário
 */
$this->load->view('dev/templates/header');
?>
<div class="container">
	<h1>Painel Administrativo</h1>
	<p>Bem-vindo <?= $user['user_username'] ?>!</p>
</div>

