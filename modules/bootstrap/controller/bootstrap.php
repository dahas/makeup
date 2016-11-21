<?php

use makeup\lib\Module;
use makeup\lib\Config;


/**
 * The name of a modules class must always be UpperCamelCase!
 * But when you create a module, you must use the name of the
 * class file (without the extension ".php").
 *
 * Class Home
 */
class Bootstrap extends Module
{
    /**
     * Calling the parent constructor is required!
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return mixed|string
     */
    public function render()
    {
        $marker["%MOD_TWBS_TABLE%"] = Module::create("twbs_table")->render();

        return $this->Template->parse($marker);
    }


    /**
     * A simple task.
     *
     * @return mixed|string
     */
    public function helloWorld()
    {
        return "Hello World!";
    }
}
