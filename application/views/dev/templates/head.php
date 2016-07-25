<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package      Template
 * @author       Natan Felles <natanfelles@gmail.com>
 */

/**
 * @var string $title Título da Página
 */
?>
<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?= $title ?></title>
	<link rel="stylesheet" href="<?= base_url('assets/dev/css/bootstrap.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/dev/css/custom.css') ?>">
</head>
<body>
