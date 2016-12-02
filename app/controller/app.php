<?php

namespace makeup\app\controller;

/*******************************************************************************
 *
 * BUILD -> PARSE -> RENDER -> EXECUTE
 * 
 * This class creates the HTML skeleton. It adds meta tags, 
 * javascript- and css-files to the related sections of the template file.
 * 
 * Use the ini-File to modify these settings: app/config/app.ini
 *
 ***************************************************************************** */

function autoloader($class)
{
	require str_replace(__NAMESPACE__, '', __DIR__) . strtolower($class) . '.php';
}

spl_autoload_register(__NAMESPACE__ . '\autoloader');

require_once "makeup/vendor/autoload.php";


use makeup\lib\Config;
use makeup\lib\Tools;
use makeup\lib\Template;
use makeup\lib\Module;


class App extends Module
{
	// Calling the parent constructor is required!
	public function __construct()
	{
		parent::__construct();

		// Simulate login:
		$_SESSION["logged_in"] = true;
	}


	/**
	 * Build the complete HTML.
	 * @param $modName			The module, that the app should render.
	 * @return mixed|string
	 */
	public function build($modName = "")
	{
		// Adds meta tags to the head section as defined in the ini files.
		$marker['%CONF_METATAGS%'] = Template::createMetaTags();

		// Adds the title to the head section as defined in the ini files.
		$marker['%TITLE%'] = Template::createTitleTag();

		// Adds stylsheet links to the head section as defined in the ini files.
		$marker['%CONF_CSS_FILES%'] = Template::createStylesheetTags();

		// Adds javascript files to the head section as defined in the ini files.
		$marker['%CONF_JS_FILES_HEAD%'] = Template::createJsFilesHeadTags();

		// Adds javascript files to the body section as defined in the ini files.
		$marker['%CONF_JS_FILES_BODY%'] = Template::createJsFilesBodyTags();

		// Connecting the navbar
		$marker["%NAVBAR%"] = $this->buildNavbar($modName);

		// Get the configuration settings of the requested module
		$modConf = Config::getFromModule($modName);
		
		#Tools::debug($modConf);

		$marker["%PAGE_TITLE%"] = isset($modConf['page_settings']['subtitle']) ? $modConf['page_settings']['subtitle'] : "";

		// Creating and rendering the requested module
		$marker["%CONTENT%"] = Module::create($modName)->render();

		return $this->getTemplate()->parse($marker);
	}
	
	
	/**
	 * Build the top navigation bar
	 * @param type $modName
	 * @return type
	 */
	private function buildNavbar($modName)
	{
		$menu = [];
		$menu["home"] = ["link" => "/", "text" => "Get started"];
		$menu["bootstrap"] = ["link" => "?mod=bootstrap", "text" => "Bootstrap Theme"];
		
		$navbar = $this->getTemplate("navbar.html");
		$navbarMenu = $navbar->getPartial("%MENU%");
		$partialNavbar["%MENU%"] = "";
		
		foreach ($menu as $item => $data)
		{
			$partialNavbar["%MENU%"] .= $navbarMenu->parse([
				"%LINK%" => $data["link"],
				"%TEXT%" => $data["text"],
				"%ACTIVE%" => $item == $modName ? 'class="active"' : ''
			]);
		}
		
		return $navbar->parse([], $partialNavbar);
	}


}

