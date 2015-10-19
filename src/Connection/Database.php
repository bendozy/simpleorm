<?php

namespace Bendozy\ORM\Connection;

use Bendozy\ORM\Enviroment;
use PDO;
use PDOException;

Class Database
{

	private static $host;
	private static $user;
	private static $pass;
	private static $dbName;
	private static $dbType;
	private static $error;
	private static $dotEnv;

	/**
	 * Get the Database Connection Instance.
	 *
	 * @return PDO
	 */
	public static function getInstance()
	{
		self::loadEnvironment();

		$options = [
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		];

		// Set DSN
		$dsn = self::$dbType . ':host=' . self::$host . ';dbname=' . self::$dbName;
		// Create a new PDO instanace
		try {
			return new PDO($dsn, self::$user, self::$pass, $options);
		} // Catch any errors
		catch(PDOException $e) {
			self::$error = $e->getMessage();
			return self::$error;
		}

	}

	/**
	 * Load the Environment variables for this class to use
	 *
	 */
	public static function loadEnvironment()
	{

		self::$dotEnv = new Enviroment();
		if(! getenv('APP_ENV')){
			self::$dotEnv->loadEnv();
		}
		self::$host = getenv('DB_HOST');
		self::$user = getenv('DB_USER');
		self::$pass = getenv('DB_PASS');
		self::$dbName = getenv('DB_NAME');
		self::$dbType = getenv('DB_TYPE');
	}
}