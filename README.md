# PHP-bandwidth

SDK for [Bandwidth's App Platform](http://ap.bandwidth.com/?utm_medium=social&utm_source=github&utm_campaign=dtolb&utm_content=)

[PHP Bandwidth Code Examples](https://github.com/bandwidthcom/php-bandwidth-examples)

# Installing:

to install, with composer

```composer require bandwidth/catapult```

OR after cloning:

```composer update```



# Running from source

You can also use Bandwidth without composer, you only need
to include "Catapult.php" from /source/

Example:

```require "source/Catapult.php"```


# API keys

REMEMBER to configure your API keys.
You can do this in 'one' of two ways:

1. Update credentials.json with your keys. If you use this method, use this constructor:
    
    ```$cred = new Catapult\Credentials; ```
    
    Also, be sure to protect that file from external access

2. Specify your keys to the Catapult client. If you use this method, use this constructor
    
```$cred = new Catapult\Credentials('your Bandwidth App Platform user-id here', 'your bandwidth app platform token here', 'your bandwidth api secret here');```

# unit tests.

In ./tests there are a list of tests to run any
we need phpunit:

composer require phpunit

and to run a rest:

php phpunit.phar --bootstrap ../source/Catapult.php {test_name} 

where test name can be any of the listed tests.


# Requirements

* needed:
* php >= 5.3.0
* libCurl

optional:
* openSSL

[![Analytics](https://ga-beacon.appspot.com/UA-77533031-1/welcome-page)](https://github.com/igrigorik/ga-beacon)
