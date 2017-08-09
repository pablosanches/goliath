<?php

namespace Goliath;

use Goliath\RuntimeException;

/**
 * Class Route
 *
 * @package Goliath
 * @author Pablo Sanches <sanches.webmaster@gmail.com>
 * @copyright Copyright 2017 (c) Pablo Sanches Software Development Inc.
 */
class Route
{
    /**
     * Long option name
     *
     * @var string
     */
    protected $longOpt;

    /**
     * Short option name
     *
     * @var string
     */
    protected $shortOpt;

    /**
     * Help text
     *
     * @var string
     */
    protected $helpText;

    /**
     * Callable to execute
     *
     * @var boolean
     */
    protected $isCallable;

    /**
     * Wether or not this route is actually an option
     *
     * @var boolean
     */
    protected $isOption = false;

    /**
     * FP to stdin if needed
     *
     * @var boolean
     */
    private $_stdin = false;

    /**
     * Get long option
     *
     * @return string
     */
    public function getLongOpt()
    {
        return $this->longOpt;
    }

    /**
     * Set long option
     *
     * @param string $longOpt
     * @return Goliath\Route
     */
    public function setLongOpt($longOpt)
    {
        switch ($longOpt) {
            case ':*':
                $this->isCallable = false;
            break;

            case ':stdin':
                $this->_stdin = fopen('php://stdin', 'r');

                if (!$this->_stdin) {
                    throw new RuntimeException('Unable to open STDIN.');
                }

                $this->isOption = false;
            break;

            default:
                $this->isOption = true;
            break;
        }

        $this->longOpt = $longOpt;

        return $this;
    }

    /**
     * Get short option
     * @return string
     */
    public function getShortOpt()
    {
        return $this->shortOpt;
    }

    /**
     * Set short option
     *
     * @param string $shortOpt
     * @return Goliath\Route
     */
    public function setShortOpt($shortOpt)
    {
        $this->shortOpt = $shortOpt;

        return $this;
    }

    /**
     * Get help text
     *
     * @return string
     */
    public function getHelp()
    {
        return $this->helpText;
    }

    /**
     * Set help text
     *
     * @param string $helpText
     * @return Goliath\Route
     */
    public function setHelp($helpText)
    {
        $this->helpText = $helpText;

        return $this;
    }

    /**
     * Get is callable
     *
     * @return boolean
     */
    public function getIsCallable()
    {
        return $this->isCallable;
    }

    /**
     * Set is callable
     *
     * @param boolean $isCallable
     * @return Goliath\Route
     */
    public function setIsCallable($isCallable)
    {
        $this->isCallable = $isCallable;

        return $this;
    }

    /**
     * Execute the command
     *
     * @param  array  $value
     * @return boolean
     */
    public function dispatch($value = array())
    {
        if (is_callable($this->isCallable)) {
            call_user_func($this->isCallable, $value);

            return true;
        }

        return false;
    }

    /**
     * Read stdin and return line by line
     *
     * @return string
     */
    public function readStdin()
    {
        if (feof($this->_stdin)) {
            return false;
        }

        return fgets($this->_stdin);
    }

    /**
     * Wether this route is a option or not
     *
     * @return boolean
     */
    public function isOption()
    {
        return $this->isOption;
    }
}
