Bandwidth SDK for PHP
=========================================================

Installing:
----------------------------------------------------------------

to install, with composer

composer require bandwidth/catapult-php-sdk


REMEMBER to configure "source/credentials.json" keys with your own.


Running unit tests.
---------------------------------------------------------------

In ./tests there are a list of tests to run any
we need phpunit:

composer require phpunit

and to run a rest:

php phpunit.phar --bootstrap ../source/Catapult.php {test_name} 

where test name can be any of the listed tests


Using in absense of composer 
---------------------------------------------------------------

to use without composer, you only need
to include "Catapult.php" from /source/
	
