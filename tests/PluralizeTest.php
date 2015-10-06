<?php

namespace Bendozy\ORM\Tests;

use Bendozy\ORM\Helper\Pluralize;

class PluralizeTest extends PHPUnit_Framework_TestCase {

	/**
	 * Tests for the pluralize() function in the Pluralize Class
	 */
	public function testPluraize()
	{
		$this->assertEquals(Pluralize::pluralize('user'), 'users');
		$this->assertEquals(Pluralize::pluralize('chair'), 'chairs');
	}
}
 