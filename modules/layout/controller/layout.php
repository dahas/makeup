<?php

use makeup\lib\Module;
use makeup\lib\Template;


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
     * Render the layout
     *
     * @param string $modName
     * @return mixed|string
     */
    public function render($modName = "")
    {
        // Connecting the navbar
        $marker["%MOD_NAVBAR%"] = $this->navbar();

        // Creating and rendering the requested module
        $marker["%MODULE%"] = Module::create($modName)->render();

        return $this->Template->parse($marker);
    }


    /**
     * Task to create the navbar
     *
     * @return mixed|string
     */
    private function navbar()
    {
        $partial = Template::load(__CLASS__, "navbar.html");
        return $partial->parse();
    }
}
