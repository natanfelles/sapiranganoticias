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
				<div class="panel-heading">Recuperação de Senha</div>
				<div class="panel-body">
					<form action="" method="post">
						<div class="form-group">
							<label for="username">Nome de Usuário</label>
							<input type="text" class="form-control" id="username" name="username">
						</div>
						<div class="form-group">
							<label for="email">E-mail</label>
							<input type="email" class="form-control" id="email" name="email">
						</div>
						<div class="text-right">
							<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
							<input type="hidden" name="recover" value="1"/>
							<button type="submit" class="btn btn-primary">Recuperar Senha</button>
						</div>
					</form>
				</div>
				<div class="panel-footer">
					<a href="<?= site_url('admin') ?>">Voltar ao Admin</a>
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
