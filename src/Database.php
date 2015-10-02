<?php

namespace Bendozy\ORM;

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


	public static function getInstance()
	{
		self::$host = '127.0.0.1:33060';
		self::$user = 'homestead';
		self::$pass = 'secret';
		self::$dbName = 'chopbox';
		self::$dbType = 'mysql';
	
		$options = [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		];
	
		// Set DSN
		$dsn = self::$dbType.':host=' . self::$host . ';dbname=' . self::$dbName;
		// Create a new PDO instanace
		try{
			return  new PDO($dsn, self::$user, self::$pass, $options);
		}
		// Catch any errors
		catch(PDOException $e){
			self::$error = $e->getMessage();
			return null;
		}
	
	}
}