<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Installer
 * @property Installer_model $installer_model
 */
class Installer extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('installer_model');
	}

	public function index()
	{
		$this->installer_model->create_tables();
		$this->load->view('installer/index');
	}
}