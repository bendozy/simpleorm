<?php
/**
 * Created by PhpStorm.
 * User: andela
 * Date: 10/6/15
 * Time: 10:09 AM
 */

namespace Verem\Persistence\Test;

use Mockery;
use Verem\persistence\Base\Model;
class PersistenceModelFindStub extends Model
{

	public static function find($id)
	{
		return 'foo';
	}
}
