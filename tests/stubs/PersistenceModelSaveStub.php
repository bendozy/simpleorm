<?php


namespace Bendozy\ORM\Tests;

use Bendozy\ORM\Base\Model;

class PersistenceModelSaveStub extends Model{
	protected $properties = [];
	public function save()
	{
		return true;
	}
} 