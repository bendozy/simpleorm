#Simple ORM
A simple lightweight php Object Relational Mapper(ORM) done according to the 
[The PHP League way](https://thephpleague.com/)


#Testing
 The phpunit framework for testing is used to perform
 unit test on the classes. The TDD principle has been
 employed

 Run this on bash to execute the tests
 ```````bash
 /vendor/phpunit/phpunit/phpunit
`````````

#Install

- To install this package, PHP 5.5.9+ and Composer are required

````bash
composer require bendozy/orm
``````

#usage

- Save a model in the database

````````
$user = new User();
$user->username = "Marcus";
$user->password = "password";
$user->email = "Marcus@andela.com";
$user->save();
`````````
- Find a model

``````
$user = User::find($id);
``````
- Update a record

``````
$user = User::find($id);
$user->password = "passwordagain";
$user->username = "helloandela";
$user->save();

``````
- Delete a record -- returns a boolean

````````
$result = User::destroy($id):
````````

- Find a model based on column value

```````
$user = User::where('username', 'john');
``````



## Change log
Please refer to [CHANGELOG](CHANGELOG.mds) file for information on what has changed recently.

## Contributing
Please check out [CONTRIBUTING](CONTRIBUTING.md) file for detailed contribution guidelines.

## Credits
Simple ORM is maintained by [Chidozie Ijeomah](https://github.com/andela-cijeomah).

## License
Simple ORM is released under the MIT Licence. See the bundled [LICENSE](LICENSE.md) file for details.


