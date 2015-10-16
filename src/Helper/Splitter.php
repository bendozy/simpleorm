<?php
/**
 * Created by PhpStorm.
 * User: bendozy
 * Date: 10/16/15
 * Time: 11:45 AM
 */

namespace Bendozy\ORM\Helper;


class Splitter
{

	/**
	 * @var $className
	 */
	private $className;

	/**
	 * @param $className
	 */
	public function __construct($className)
	{
		$this->className = $className;
	}

	/**
	 * Split the word at every occurence of an
	 * uppercase letter.
	 *
	 * @return array
	 */
	public function split()
	{
		return preg_split('/(?=[A-Z])/', $this->className);
	}

	/**
	 * convert the input string to its lowercase
	 * version.
	 *
	 * @return array
	 */
	public function toLower()
	{
		$lowerCase = [];
		foreach($this->split() as $key => $value){
			$lowerCase[] = strtolower($value);
		}

		return $lowerCase;
	}

	/**
	 * Format the string, remove any trailing underscores
	 * that might be added to the beginning of the name.
	 *
	 * @return string
	 */
	public function format()
	{
		$formattedString = join('_', $this->toLower());
		if(strpos($formattedString, '_') === 0) {
			$formattedString = substr($formattedString, 1);
		}

		return $formattedString;
	}
}