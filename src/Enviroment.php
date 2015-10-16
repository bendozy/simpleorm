<?php

namespace Bendozy\ORM;

use Dotenv\Dotenv;

class Enviroment extends Dotenv {

	private $dotEnv;

	public function __construct()
	{
		$this->dotEnv = parent::__construct($_SERVER['DOCUMENT_ROOT']);
	}
	public function loadEnv()
	{
		return $this->load();
	}
}