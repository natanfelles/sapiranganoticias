<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package      Admin
 * @author       Natan Felles <natanfelles@gmail.com>
 *
 * @var array $message Mensagem
 * @var array $csrf    Proteção contra CSRF
 */
?>
<div class="container" style="padding-top: 70px">
	<div class="row">
		<div class="col-md-4 col-center">
			<div class="panel panel-default">
				<div class="panel-heading">Tela Bloqueada</div>
				<div class="panel-body">
					<form action="<?= site_url('admin/lock') ?>" method="post">
						<div class="form-group">
							<label for="username">Nome de Usuário</label>
							<input type="text" class="form-control" id="username" name="username" value="<?= $user['user_username'] ?>" disabled>
						</div>
						<div class="form-group">
							<label for="password">Senha</label>
							<input type="password" class="form-control" id="password" name="password">
						</div>
						<div class="text-right">
							<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
							<input type="hidden" name="unlock" value="1"/>
							<button type="submit" class="btn btn-primary">Desbloquear</button>
						</div>
					</form>
				</div>
				<div class="panel-footer">
					<!--
					@todo Submit para limpar todos os dados/renovar sessão
					-->
					<a href="<?= site_url('admin') ?>">Entrar com Outro Usuário</a>
				</div>
			</div>
			<?php
			if (isset($message['type'])):
				?>
				<div class="alert alert-<?= $message['type'] ?>" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
						<span aria-hidden="true">&times;</span></button>
					<?= $message['content'] ?>
				</div>
				<?php
			endif;
			?>
		</div>
	</div>
</div>
