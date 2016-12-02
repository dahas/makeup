<?php

use makeup\lib\Module;
use makeup\lib\Template;
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
     * Task that renders the layout
     *
     * @param string $modName
     * @return mixed|string
     */
    public function render($modName = "")
    {
        return $this->getTemplate()->parse();
    }
		
		public function debug()
		{
			$partial = Template::load(__CLASS__, "layout_1.html");
			return $partial->parse();
		}
}
