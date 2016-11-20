<?php

use makeup\lib\Module;
use makeup\lib\Config;


/**
 * The name of a modules class must always be UpperCamelCase!
 * But when you create a module, you must use the name of the
 * class file (without the extension ".php").
 *
 * Class Layout
 */
class Layout extends Module
{
    /**
     * Calling the parent constructor is required!
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @param string $modName
     * @param string $task
     * @return mixed|string
     */
    public function render($modName = "", $task = "render")
    {
        $Module = Module::create($modName); // The factory pattern returns an object of a module.
        $html = $Module->$task(); // The module executes the requested task.

        $marker["%MODULE%"] = !$html || $modName == "layout" ? "" : $html;

        return $this->Template->parse($marker);
    }
}
