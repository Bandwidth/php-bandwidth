Bandwidth SDK for PHP
=========================================================

Installing:
----------------------------------------------------------------

to install, with composer

composer require bandwidth/catapult

OR after cloning:

composer update


Always getting a stable copy
---------------------------------------------------------------

As we test newer releases, functionality may not always
be identical to a major release, to obtain the mininum stable (currently 0.5.1)
use:

composer require bandwidth/catapult:0.5.1



API keys
---------------------------------------------------------------


REMEMBER to configure "source/credentials.json" keys with your own.
You should always set r/w to this file safe incase of using over
a web server.


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
	
