<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package      Admin
 * @author       Natan Felles <natanfelles@gmail.com>
 */

/**
 * @var array $csrf Proteção contra CSRF
 */
?>
<body>
<div class="container">
	<h1>Painel Administrativo</h1>
	<form action="" method="post">
		<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
		<input type="hidden" name="logout" value="1"/>
		<button type="submit" class="btn btn-danger">Desconectar</button>
	</form>
</div>

