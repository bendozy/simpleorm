<?php
/**
 * Created by PhpStorm.
 * User: bendozy
 * Date: 10/6/15
 * Time: 10:14 AM
 */

namespace Bendozy\ORM\Exceptions;

use Exception;

/**
 * Class ModelNotFoundException
 *
 * When the model is not found
 *
 * @package Bendozy\ORM\Exceptions
 *
 * @author Chidozie Ijeomah
 */
class ModelNotFoundException extends Exception
{
	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @param string $message
	 */
	public function __construct($message)
	{
		$this->message = $message;
	}

	/**
	 * @method getExceptionMessage
	 *
	 * returns an error message to the calling
	 * method.
	 *
	 * usage $e->getExceptionMessage();
	 *
	 * @return string
	 */
	public function getExceptionMessage()
	{
		return $this->message;
	}
} 