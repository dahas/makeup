<?php

use makeup\lib\Module;


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
        /**
         * secureRender() takes care of the setting "mod_settings|protected". If protected is
         * set to 1 and the user isn´t logged in, the module won´t be rendered.
         */
        $marker["%MOD_TWBS_TABLE%"] = Module::create("twbs_table")->secureRender();

        return $this->getTemplate()->parse($marker);
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
