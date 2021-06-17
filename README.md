<p align="center">
    <img src="http://i.imgur.com/va7E8tX.png" width="300px">
</p>

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/f0ef0fe427634e719a54f6b7d0b00f93)](https://www.codacy.com/gh/pablosanches/goliath/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=pablosanches/goliath&amp;utm_campaign=Badge_Grade)
[![Build Status](https://travis-ci.org/pablosanches/goliath.svg?branch=master)](https://travis-ci.org/pablosanches/goliath)

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
