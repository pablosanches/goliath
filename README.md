<p align="center">
    <img src="http://i.imgur.com/va7E8tX.png" width="300px">
</p>

[![Build Status](https://travis-ci.org/pablosanches/goliath.svg?branch=master)](https://travis-ci.org/pablosanches/goliath)
[![CodeClimate](http://img.shields.io/codeclimate/github/pablosanches/goliath.svg?style=flat)](https://codeclimate.com/github/pablosanches/goliath)

A simple framework to facilitate the development of CLI applications based on PHP command.

Features
========
* Routing of command line arguments
* Short and long arguments
* Automagic `--help` creation
* Configuration

Requirements
============
PHP >=5.6

Example
=======
```php
use Goliath\Cli;

$app = new Goliath\Cli();

$app = new \Goliath\Cli(array(
    'debug' => true,
    'log.destination' => \Goliath\Logger::LOG_STDERR,
    'log.dir' => __DIR__
));

$app->command('hello-world:',
    function($name) {
        echo "Hello $name\n";
    })
    ->setHelp('Hello world example');
```

Installation
============
You only need to include the depency in the composer.
```json
"require": {
    "pablosanches/goliath": "1.0.*"
}
```

And run the composer
```
$ composer install
```
