<?php


namespace Bendozy\ORM\Tests;

use Bendozy\ORM\Tests\Stub\PersistenceModelStub as ModelStub;
use Mockery;

class ModelTest extends \PHPUnit_Framework_TestCase
{

	public $model;

	public function setUp()
	{
		$this->model = new ModelStub();
	}

	/**
	 * Test the getTableName methods.
	 */
	public function testTableName()
	{
		$mock = Mockery::mock($this->model);
		$mock->shouldReceive('getTableName')
			->once()
			->andReturn('model_stubs');
	}

	/**
	 * Test the getClassName.
	 */
	public function testClassName()
	{
		$mock = Mockery::mock($this->model);
		$mock->shouldReceive('getClassName')
			->once()
			->andReturn('ModelStub');
	}

	/**
	 * Test if model attributes can be set and manipulated.
	 */
	public function testAttributesCanBeSetAndManipulated()
	{
		$model = $this->model;
		$model->name = "Test";
		$this->assertEquals('Test', $model->name);
		$this->assertNotNull($model->name);

		//Change the model attributes
		$model->name = "Hello";
		$this->assertEquals('Hello', $model->name);
	}

	/**
	 * Test to see if a new model instance has unpopulated properties
	 */
	public function testNewInstanceCreatesInstanceWithoutAttributes()
	{
		$model = new ModelStub();
		$this->assertFalse(isset($model->username));
	}

	/**
	 * Test the find method via Mockery
	 */
	public function testFind()
	{
		$mock = Mockery::mock($this->model);
		$mock->shouldReceive('find')
			->with(1)
			->once()
			->andReturn('Found');
	}

	/**
	 * Test the destroy method via Mockery
	 */
	public function testDestroy()
	{
		$mock = Mockery::mock($this->model);
		$mock->shouldReceive('destroy')
			->with(1)
			->once()
			->andReturn(true);
	}

	/**
	 * Test the destroy method via Mockery
	 */
	public function testSave()
	{
		$mock = Mockery::mock($this->model);
		$mock->shouldReceive('save')
			->with($this->model)
			->once()
			->andReturn(true);
	}
}
 