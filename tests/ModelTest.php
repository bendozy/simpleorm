<?php
/**
 * Created by PhpStorm.
 * User: bendozy
 * Date: 10/6/15
 * Time: 11:43 AM
 */

namespace Bendozy\ORM\Tests;

use Mockery;
use Bendozy\ORM\Tests\PersistenceModelSaveStub;

class ModelTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		parent::setUp();

		$this->mock = $this->mock('Cribbb\Storage\User\UserRepository');
	}
	public function testModelCanBeDeleted()
	{
		$mock = Mockery::mock('Bendozy\ORM\Base\Model\PersistenceModelDeleteStub');
		$mock->shouldReceive('delete')
			->with(1)
			->once()
			->andReturn(true);
	}
}
 