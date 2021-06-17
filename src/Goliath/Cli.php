<?php

namespace Goliath;

use Goliath\View;
use Goliath\Logger;
use Goliath\Route;

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
    private $_currentRoute = null;

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

    /**
     * The constructor
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->appname = basename($_SERVER['argv'][0]);
        set_error_handler(array('\Goliath\Cli', 'handleErrors'));

        $this->_options = array_merge(
            array(
                'template.path' => __DIR__ . '/views/',
                'debug' => false,
                'log.dir' => __DIR__,
                'log.severity' => Logger::INFO,
                'log.destination' => Logger::LOG_STDOUT
            ),
            $options
        );

        $this->_view = new View();
        $this->_view->setPath($this->_options['template.path']);

        $this->_logger = new Logger(
            $this->_options['log.dir'],
            $this->_options['log.severity'],
            $this->_options['log.destination']
        );
    }

    /**
     * Cli error handler
     *
     * @param  integer  $errNo
     * @param  string   $errStr
     * @param  string   $errFile
     * @param  string   $errLine
     * @return boolean
     */
    public static function handleErrors(
        $errNo,
        $errStr = null,
        $errFile = null,
        $errLine = null
    ) {
        if (error_reporting() && $errNo) {
            throw new \ErrorException($errStr, $errNo, 0, $errFile, $errLine);
        }

        return true;
    }

    /**
     * Add route for a command
     *
     * ARGUMENTS:
     *
     * first: Long option (REQUIRED)
     * second: Short option or command (REQUIRED)
     * third: Command (OPTIONAL)
     *
     * @param  mixed $args
     * @return Goliath\Route
     */
    public function command($args = false)
    {
        $args = func_get_args();

        $longOpt = array_shift($args);
        $t = array_shift($args);

        if (is_callable($t)) {
            $shortOpt = '';
            $command = $t;
        } else {
            $shortOpt = $t;
            $command = array_shift($args);
        }

        if (!is_callable($command)) {
            throw new \Exception('Command not Callable');
        }

        $route = new Route();
        $route
            ->setLongOpt($longOpt)
            ->setShortOpt($shortOpt)
            ->setIsCallable($command);

        $this->_routes[] = $route;

        return $route;
    }

    /**
     * Add a option
     *
     * @param  string $longOpt
     * @param  string $value
     * @return string
     */
    public function option($longOpt, $value = null)
    {
        if (is_null($value) && isset($this->_options[$longOpt])) {
            return $this->_options[$longOpt];
        }

        $this->_options[$longOpt] = $value;
    }

    /**
     * Source option from a ini File
     *
     * @param  string $file
     * @return void
     */
    public function configure($file)
    {
        $config = parse_ini_file($file);

        if (!empty($config)) {
            foreach ($config as $key => $value) {
                $this->option($key, $value);
            }
        }
    }

    /**
     * Autoload classes
     *
     * @param  string $class
     * @return void
     */
    public static function autoload($class)
    {
        if (strpos($class, 'Cli') !== 0) {
            return;
        }

        $fileName = str_replace('_', DIRECTORY_SEPARATOR, substr($class, 5));
        $fileName = str_replace('\\', '', $fileName);

        $file = dirname(__DIR__)
            . '/'
            . str_replace('_', DIRECTORY_SEPARATOR, $fileName)
            . '.php';

        if (file_exists($file)) {
            include_once $file;
        }
    }

    /**
     * Return the currently executed Route object
     *
     * @return Goliath\Route
     */
    public function route()
    {
        return $this->_currentRoute;
    }

    /**
     * notFound handler
     *
     * @param  mixed $isCallable
     * @return void
     */
    public function notFound($isCallable = null)
    {
        if (!is_null($isCallable) && is_callable($isCallable)) {
            $this->_userNotFound = $isCallable;
            return;
        } else if (!is_null($isCallable)) {
            throw new \Exception('Passed a non callable function.');
        } else if (!is_null($this->_userNotFound)) {
            call_user_func($this->_userNotFound);
            return;
        }

        $str = 'Usage: ' . $this->appname . " [OPTION]...\n";

        $lines = array();
        $longest = 0;
        foreach ($this->_routes as $key => $route) {
            if (!$route->isOption()) {
                continue;
            }

            $line = '  ';
            if ($route->getShortOpt()) {
                $line .= '-' . rtrim($route->getShortOpt(), ':') . ', ';
            } else {
                $line .= '    ';
            }

            $longOpt = rtrim($route->getLongOpt(), ':');
            if (strpos($route->getLongOpt(), ':') !== false) {
                $longOpt .= '=<VALUE>';
            }
            $line .= '--' . $longOpt;

            if (strlen($line) > $longest) {
                $longest = strlen($line);
            }

            $lines[$key] = $line;
        }

        foreach ($lines as $key => $line) {
            $str .= sprintf("%-{$longest}s ", $line);
            $str .= $this->_routes[$key]->getHelp();
            $str .= "\n";
        }

        echo $str;
        exit;
    }

    /**
     * Return the current _view
     *
     * @return Goliath\View
     */
    public function view()
    {
        return $this->_view;
    }

    /**
     * Return the current _logger
     *
     * @return Goliath\Logger
     */
    public function log()
    {
        return $this->_logger;
    }

    /**
     * Run
     *
     * @return void
     */
    public function run()
    {
        if (PHP_SAPI !== 'cli') {
            throw new \Exception('This is a Command Line Application.');
        }

        try {

            // Go through all routes and execute commands
            $dispatched = false;
            foreach ($this->_routes as $route) {
                $this->_currentRoute = $route;

                if (!$route->isOption()) {
                    $dispatched = $route->dispatch();
                    continue;
                }

                // Parse command line with getopt, one route at a time
                $options =  getopt(
                    $route->getShortOpt(),
                    array(
                        $route->getLongOpt()
                    )
                );

                if (!empty($options)) {
                    foreach ($options as $key => $value) {
                        if (
                            rtrim($route->getShortOpt(), ':') === $key ||
                            rtrim($route->getLongOpt(), ':') === $key
                        ){
                            $dispatched = $route->dispatch($value);
                            continue;
                        }
                    }
                }
            }

            $this->_currentRoute = null;

            if (!$dispatched) {
                // No (valid) options give
                echo $this->notFound();
            }

        } catch (\Exception $e) {
            if ($this->option('debug')) {
                throw new \Exception($e);
            } else {
                return "ERROR: " . $e->getMessage() . "\n";
            }
        }
    }
}
