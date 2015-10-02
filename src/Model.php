<?php

namespace Bendozy\ORM;

use PDO;

abstract class Model
{
	public $last_error_message;
	protected $properties = array();
	protected static $primaryKey = 'id';

	public function __get($key)
	{
		return $this->properties[$key];
	}

	public function __set($key, $value)
	{
		return $this->properties[$key] = $value;
	}

	public function validate()
	{
		return true;
	}
    public static function getClassName()
    {
	    $class = get_called_class();
	    $class = explode( "\\", $class );
        return end($class);
    }

	public static function getTableName()
	{
		return Pluralize::pluralize(self::getClassName());
	}

	public static function all()
	{
		$dbh = Database::getInstance();
		$sql = "SELECT * FROM " . self::getTableName();
		$result = $dbh->prepare($sql);
		$result->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$result->execute();
		return $result->fetchAll();
	}

	public static function find($id)
	{   $dbh = Database::getInstance();
		$sql = "SELECT * FROM " . self::getTableName() . " WHERE " . self::$primaryKey . " = ?";
		$result = $dbh->prepare($sql);
		$result->execute(array($id));
		$result->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$objects = $result->fetchAll();

		if(count($objects) == 1) {
			return $objects[0];
		} else{
			return $objects;
		}
	}

	public function exists()
	{
		if(isset($this->properties) && isset($this->properties[self::$primaryKey])
			&& is_numeric($this->properties[self::$primaryKey])
		) {
			return true;
		} else{
			return false;
		}
	}

	public function save() {

		if($this->validate() === false) { return false; }

		if($this->exists()) {
			$run = $this->executeUpdateQuery();
		} else {
			$run = $this->executeInsertQuery();
		}

        var_dump($run);

		if($run === true) {
			if(!$this->exists()) {
				//$this->id = $this->db->lastInsertId();
			}
			//$this->loadPropertiesFromDatabase();
			return true;
		} else {
			//$this->sql_error = $q->errorInfo();
			return false;
		}
	}

	public function executeInsertQuery()
	{
		$total_properties_count = count($this->properties);
		$x = 0;

		$sql = "INSERT INTO ".self::getTableName()." (";
		$sqlSetColumns = "";
		$sqlSetValues = "";
		foreach($this->properties as $key => $value) {
			$x++;
			if($key == self::$primaryKey) { continue; }
			$sqlSetColumns .=  $key;
			$sqlSetValues .=  ":".$key;
			if($x != $total_properties_count) {
				$sqlSetColumns .= ", ";
				$sqlSetValues .= ", ";
			}
		}

		$sql .= $sqlSetColumns . " ) VALUES ( " . $sqlSetValues. " )";
		$dbh = Database::getInstance();
		$stmt = $dbh->prepare($sql);
		foreach($this->properties as $key => $value) {
			$stmt->bindParam(':' . $key, $value);
		}
		return $stmt->execute();
	}

	public function executeUpdateQuery()
	{
		$total_properties_count = count($this->properties);
		$x = 0;
        $sql = "UPDATE ".self::getTableName()." SET ";//task_name, created_at, updated_at, status ) VALUES (:task_name, :created_at, :updated_at, :status)";
		$sqlSetColumns = "";
		$valueArray = [];
		foreach($this->properties as $key => $value) {
			$x++;
			if($key == self::$primaryKey) {
				$valueArray[":".$key] = $value;
				continue;
			}
			if(isset($value)){
				$sqlSetColumns .=  $key . " = :".$key;
				$valueArray[":".$key] = $value;
			}

			if($x != $total_properties_count) {
				if(isset($value)){$sqlSetColumns .= ", ";}
			}
		}

		$sql .= $sqlSetColumns . " WHERE " . self::$primaryKey. " = :". self::$primaryKey;
		echo $sql;

		$dbh = Database::getInstance();
		$stmt = $dbh->prepare($sql);
		var_dump($valueArray);
		return $stmt->execute($valueArray);
	}

}