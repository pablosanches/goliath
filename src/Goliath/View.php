<?php

namespace Goliath;

use Goliath\RuntimeException;

/**
 * Class View
 *
 * @package Goliath
 * @author Pablo Sanches <sanches.webmaster@gmail.com>
 * @copyright Copyright 2017 (c) Pablo Sanches Software Development Inc.
 */
class View
{
    /**
     * Template variables
     *
     * @var array
     */
    private $_vars = array();

    /**
     * Directory to template
     *
     * @var string
     */
    private $_templatePath = null;

    /**
     * Set template variable
     *
     * @param string $name
     * @param string $value
     * @return Goliath\View
     */
    public function setVar($name, $value)
    {
        $this->_vars[$name] = $value;

        return $this;
    }

    /**
     * Set directory to template
     * @param string $path
     * @return Goliath\View
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Open, parse and return the template file
     *
     * @param  string $file
     * @return string
     */
    public function fetch($file)
    {
        // Extract the vars to local namespace
        extract($this->_vars);

        // Start output buffering
        ob_start();

        // Include de file
        include $this->path . $file;

        // Get the contents of the buffer
        $contents = ob_get_contents();

        // Return output String
        ob_end_clean();

        return $contents;
    }

    /**
     * Display the template directly
     *
     * @param  string $file
     * @return void
     */
    public function display($file)
    {
        echo $this->fetch($file);
    }
}
