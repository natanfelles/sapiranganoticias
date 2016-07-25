<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package      Admin
 * @author       Natan Felles <natanfelles@gmail.com>
 *
 * @var array $user Dados do Usuário
 * @var array $csrf Proteção contra CSRF
 */
?>
<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">

		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Fechar</span> <span class="icon-bar"></span>
				<span class="icon-bar"></span> <span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?= site_url('admin') ?>">Sapiranga Notícias</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li <?= uri_string() == 'admin' ? 'class="active"' : ''; ?>>
					<a href="<?= site_url('admin') ?>">Painel</a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown
						<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li>
							<a href="#">Action</a>
						</li>
						<li>
							<a href="#">Another action</a>
						</li>
						<li>
							<a href="#">Something else here</a>
						</li>
						<li role="separator" class="divider"></li>
						<li>
							<a href="#">Separated link</a>
						</li>
						<li role="separator" class="divider"></li>
						<li>
							<a href="#">One more separated link</a>
						</li>
					</ul>
				</li>
			</ul>
			<form class="navbar-form navbar-left" role="search">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Search">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>

			<form action="<?= site_url('admin') ?>" method="post" class="navbar-form navbar-right">
				<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
				<input type="hidden" name="logout" value="1"/>
				<button type="submit" class="btn btn-danger">Desconectar</button>
			</form>

			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="#">Link</a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $user['user_username'] ?>
						<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li <?= uri_string() == 'admin/profile' ? 'class="active"' : ''; ?>>
							<a href="<?= site_url('admin/profile') ?>">Perfil</a>
						</li>
						<li role="separator" class="divider"></li>
						<li>
							<!-- @todo Trocar para formulário com csrf -->
							<a href="<?= site_url('admin/lock') ?>">Bloquear Tela</a>
						</li>
					</ul>
				</li>
			</ul>


		</div><!-- /.navbar-collapse -->

	</div><!-- /.container-fluid -->
</nav>