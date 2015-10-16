<?php

namespace Bendozy\ORM\Base;

use PDO;
use Bendozy\ORM\Helper\Splitter;
use Bendozy\ORM\Helper\Pluralize;
use Bendozy\ORM\Connection\Database;
use Bendozy\ORM\Exceptions\ModelNotFoundException;

abstract class Model
{
	/**
	 * The model's attributes.
	 *
	 * @var array
	 */
	protected $properties = [];

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected static $primaryKey = 'id';

	/**
	 * Dynamically retrieve attributes on the model.
	 *
	 * @param  string $key
	 *
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->properties[$key];
	}

	/**
	 * Dynamically set attributes on the model.
	 *
	 * @param  string $key
	 * @param  mixed $value
	 *
	 * @return void
	 */
	public function __set($key, $value)
	{
		return $this->properties[$key] = $value;
	}

	/**
	 * Validate the Model's attributes.
	 *
	 * @return boolean
	 */
	public function validate()
	{
		return true;
	}

	/**
	 * Get the Short Class Name of the current class.
	 *
	 * @return string
	 */
	public static function getClassName()
	{
		$class = get_called_class();
		$class = explode("\\", $class);

		return end($class);
	}

	/**
	 * Get the name of the table in the database that is tied to this model.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		$splitter = new Splitter(self::getClassName());

		$tableName = $splitter->format();
		$tableName = Pluralize::pluralize($tableName);

		return $tableName;
	}

	/**
	 * Get all the instances of the model from the database.
	 *
	 * @return array
	 */
	public static function all()
	{
		try {
			$dbh = Database::getInstance();

			$sql = "SELECT * FROM " . self::getTableName();

			$result = $dbh->prepare($sql);
			$result->setFetchMode(PDO::FETCH_CLASS, get_called_class());
			$result->execute();

			return $result->fetchAll();
		} catch(PDOException $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Find a Model with the given primary key.
	 *
	 * @param  int $id
	 *
	 * @throws \Bendozy\ORM\Exceptions\ModelNotFoundException
	 * @return Model
	 */
	public static function find($id)
	{

		try {
			$dbh = Database::getInstance();

			$sql = "SELECT * FROM " . self::getTableName() . " WHERE " . self::$primaryKey . " = ?";

			$result = $dbh->prepare($sql);
			$result->execute([$id]);
			$result->setFetchMode(PDO::FETCH_CLASS, get_called_class());

			$objects = $result->fetchAll();

			if($objects == null) {
				throw new ModelNotFoundException('The Model is not found');
			}

			if(count($objects) == 1) {
				return $objects[0];
			} else{
				return $objects;
			}
		} catch(PDOException $e) {
			return $e->getMessage();
		}

	}

	/**
	 * Return Model(s) where the column names and values are given.
	 *
	 * @param  string $columnName
	 * @param   $value
	 *
	 * @throws \Bendozy\ORM\Exceptions\ModelNotFoundException
	 * @return Model
	 */
	public static function where($columnName, $value)
	{

		try {
			$dbh = Database::getInstance();

			$sql = "SELECT * FROM " . self::getTableName() . " WHERE " . $columnName . " = ?";

			$result = $dbh->prepare($sql);
			$result->execute([$value]);
			$result->setFetchMode(PDO::FETCH_CLASS, get_called_class());

			$objects = $result->fetchAll();

			if($objects == null) {
				throw new ModelNotFoundException('The Model is not found');
			}

			if(count($objects) == 1) {
				return $objects[0];
			} else{
				return $objects;
			}
		} catch(PDOException $e) {
			return $e->getMessage();
		}

	}

	/**
	 * Delete the model with the given primary key.
	 *
	 * @param  int $id
	 *
	 * @return boolean
	 */
	public static function destroy($id)
	{
		try {
			$dbh = Database::getInstance();
			$sql = "DELETE FROM " . self::getTableName() . " WHERE " . self::$primaryKey . " = ?";
			$result = $dbh->prepare($sql);
			return $result->execute(array($id));
		} catch(PDOException $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Check if the model has been persisted to the database.
	 *
	 * @return boolean
	 */
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

	/**
	 * Save model to the database or update model in Database.
	 *
	 * @return boolean
	 */
	public function save()
	{
		if(! $this->validate()) {
			return false;
		}

		if($this->exists()) {
			return $this->executeUpdateQuery();
		} else{
			return $this->executeInsertQuery();
		}
	}

	/**
	 * Perform an insert operation.
	 *
	 * @return boolean
	 */
	public function executeInsertQuery()
	{
		$total_properties_count = count($this->properties);
		$x = 0;

		$sql = "INSERT INTO " . self::getTableName() . " (";
		$sqlSetColumns = "";
		$sqlSetValues = "";
		foreach($this->properties as $key => $value){
			$x++;
			if($key == self::$primaryKey) {
				continue;
			}
			$sqlSetColumns .= $key;
			$sqlSetValues .= ":" . $key;
			if($x != $total_properties_count) {
				$sqlSetColumns .= ", ";
				$sqlSetValues .= ", ";
			}
		}

		$sql .= $sqlSetColumns . " ) VALUES ( " . $sqlSetValues . " )";

		try {
			$dbh = Database::getInstance();
			$stmt = $dbh->prepare($sql);
			foreach($this->properties as $key => $value){
				$stmt->bindValue(':' . $key, $value);
			}
			return $stmt->execute();
		} catch(PDOException $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Perform an insert operation.
	 *
	 * @return boolean
	 */
	public function executeUpdateQuery()
	{
		$total_properties_count = count($this->properties);
		$x = 0;
		$sql = "UPDATE " . self::getTableName() . " SET ";
		$sqlSetColumns = "";
		$valueArray = [];

		foreach($this->properties as $key => $value){
			$x++;
			if($key == self::$primaryKey) {
				$valueArray[":" . $key] = $value;
				continue;
			}

			if(isset($value)) {
				$sqlSetColumns .= $key . " = :" . $key;
				$valueArray[":" . $key] = $value;
			}

			if($x != $total_properties_count) {
				if(isset($value)) {
					$sqlSetColumns .= ", ";
				}
			}
		}

		$sql .= $sqlSetColumns . " WHERE " . self::$primaryKey . " = :" . self::$primaryKey;

		try {
			$dbh = Database::getInstance();
			$stmt = $dbh->prepare($sql);

			return $stmt->execute($valueArray);
		} catch(PDOException $e) {
			return $e->getMessage();
		}
	}

}