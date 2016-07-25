<?php
/**
 * @var array $csrf
 * @var array $user Dados do Usuário
 */
//echo '<pre>';
//print_r($user);
?>
<body>
<div class="container">
	<h1>Perfil</h1>
	<?php
	if (isset($message)):
		?>
		<div class="alert alert-<?= $message['type'] ?>" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
				<span aria-hidden="true">&times;</span></button>
			<?= $message['content'] ?>
		</div>
		<?php
	endif;
	?>
	<form action="" method="post">
		<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
		<input type="hidden" name="profile" value="1"/>
		<div class="form-group">
			<label for="username">Nome de Usuário:</label>
			<input type="text" class="form-control" id="username" name="username" value="<?= $user['user_username'] ?>">
		</div>
		<div class="form-group">
			<label for="email">E-mail:</label>
			<input type="email" class="form-control" id="email" name="email" value="<?= $user['user_email'] ?>">
		</div>
		<div class="form-group">
			<label for="password">Senha:</label>
			<input type="password" class="form-control" id="password" name="password">
		</div>
		<div class="form-group">
			<label for="password_repeat">Confirme a Senha:</label>
			<input type="password" class="form-control" id="password_repeat" name="password_repeat">
		</div>
		<button type="submit" class="btn btn-success">Atualizar Perfil</button>
	</form>
</div>
