<?php

namespace Goliath\Tests;

use Goliath\Cli;

class CliTest extends \PHPUnit_Framework_TestCase
{
    public function setUp() {}

    public function testInitialize()
    {
        $app = new Cli();
        $this->assertInstanceOf('Goliath\Cli', $app);

        return $app;
    }

    /**
     * @depends testInitialize
     * @return Goliath\Cli $app
     */
    public function testCommand($app)
    {
        $route = $app->command('hello-world:',
            function($name) {
            echo "Hello $name\n";
            }
        );

        $this->assertInstanceOf('Goliath\Route', $route);

        return $app;
    }
}
