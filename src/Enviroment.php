<?php

namespace Bendozy\ORM;

use Dotenv\Dotenv;

class Enviroment extends Dotenv {

	private $dotEnv;

	public function __construct()
	{
		$this->dotEnv = parent::__construct(__DIR__.'/../');
	}
	public function loadEnv()
	{
		return $this->load();
	}
}