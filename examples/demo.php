#!/usr/bin/env php

<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Goliath', __DIR__);

use Goliath\Cli;

$app = new Goliath\Cli();

$app = new \Goliath\Cli(array(
    'debug' => true,
    'log.destination' => \Goliath\Logger::LOG_STDERR,
    'log.dir' => __DIR__
));

/**
 * Help text
 */
$app->command('help', 'h',
    function() use ($app) {
        echo $app->notFound();
        exit;
    })
    ->setHelp('This Help text');

/**
 * Hello world long option with a parameter
 */
$app->command('hello-world:',
    function($name) {
        echo "Hello $name\n";
    })
    ->setHelp('Hello world example');

/**
* A Route that is executed without a command line option
**/
$app->command(':*',
    function() use ($app) {
        $app->log()->notice('Hello World Logging');
        $app->view()->set('name', 'value');
        echo "Always executed.\n";
    });

/**
* A route that captures piped data from stdin
**/
/*
$app->command(':stdin',
    function() use ($app) {
        while (($data = $app->route()->readStdin()) !== false) {
            print_r($data);
        }
    });
*/

/**
* Execute The App
**/
$app->run();
