<?php
/**
 * Created by PhpStorm.
 * User: bendozy
 * Date: 9/28/15
 * Time: 1:01 PM
 */
    require('vendor/autoload.php');
    use \Bendozy\ORM\User;

	$user = new User();
    print_r($user);
    $user->email = "dude okay now";
    $user->password = "ha hello there";
    $user->username = "hello here we go";
	print_r($user);
    $user->save();


