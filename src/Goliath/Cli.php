<?php

namespace Goliath;

use Goliath\View;
use Goliath\Route;
use Goliath\Logger;

/**
 * Class Cli
 *
 * @package Goliath
 * @author Pablo Sanches <sanches.webmaster@gmail.com>
 * @copyright Copyright 2017 (c) Pablo Sanches Software Development Inc.
 */
class Cli
{
    /**
     * The name of app
     *
     * @var string
     */
    public $appname;

    /**
     * The routes
     *
     * @var array
     */
    private $_routes = array();

    /**
     * The currently route
     *
     * @var string
     */
    private $_currentNode = null;

    /**
     * The options
     *
     * @var array
     */
    private $_options = array();

    /**
     * Custom user set notFound function
     *
     * @var mixed
     */
    private $_userNotFound = null;

    /**
     * Goliath view
     *
     * @var Goliath\View
     */
    private $_view = null;

    /**
     * Goliath logger
     *
     * @var Goliath\Logger;
     */
    private $_logger = null;
}
