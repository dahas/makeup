<?php

namespace makeup\lib;

use DI\ContainerBuilder;


/**
 * Abstract Class Module
 * 
 * @package makeup\lib\interfaces
 */
abstract class Module
{
	private $Template = null;
	protected $RQ = array();
	protected $config = array();


	public function __construct()
	{
		$modNsArr = explode("\\", get_class($this));
		$className = array_pop($modNsArr);
		$moduleFileName = Tools::camelCaseToUnderscore($className);

		Config::init($moduleFileName); // Loads config.ini

		$this->RQ = Tools::parseQueryString();

		if ($className == "App")
			$this->Template = Template::load("App", "app.html");
		else
			$this->Template = Template::load($className, "$moduleFileName.html");
	}


	/**
	 *
	 * @return ErrorMod|mixed
	 * @throws \Exception
	 */
	public static function create()
	{
		$args = func_get_args();
		$types = array();
		foreach ($args as $arg) {
			$types[] = gettype($arg);
		}

		// First argument must be the module name:
		if (!isset($args[0]) || $types[0] != "string" || !$args[0]) {
			throw new \Exception('Not a valid classname!');
		} else {
			$name = $args[0];
			$className = Tools::upperCamelCase($name);
		}

		$modFile = "makeup/modules/$name/controller/$name.php";

		if (is_file($modFile)) {
			$builder = new ContainerBuilder();
			$builder->useAutowiring(false);
			$builder->useAnnotations(true);
			$container = $builder->build();
			require_once $modFile;
			return $container->get($className);
		} else {
			return new ErrorMod($className);
		}
	}


	/**
	 * Returns the rendered HTML.
	 *
	 * @return mixed
	 */
	abstract public function render();
	
	
	/**
	 * Takes care of the setting "mod_settings|protected".
	 * If protected is set to 1 and the user isn´t logged in, 
	 * the module won´t be rendered.
	 *
	 * @return mixed|void
	 */
	public function secureRender()
	{
		if (Config::get('mod_settings', 'protected') == "1" 
			&& (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] == false))
			return null;
		else
			return $this->render();
	}


	/**
	 * Returns the template object
	 *
	 * @return Template|null
	 */
	public function getTemplate()
	{
		return $this->Template;
	}


	/**
	 *
	 * @param
	 *            $method
	 * @param
	 *            $args
	 * @return string
	 */
	public function __call($method, $args)
	{
		return Tools::errorMessage("Task $method() not defined!");
	}


	/**
	 * Destructor
	 */
	public function __destruct()
	{
		unset($this->Template);
		unset($this);
	}


}


/**
 * Class ErrorMod
 * 
 * @package makeup\lib
 */
class ErrorMod
{
	private $modName = "";


	public function __construct($modName)
	{
		$this->modName = strtolower("mod_$modName");
	}


	public function render()
	{
		return Tools::errorMessage("Module '$this->modName' not found!");
	}


	public function secureRender()
	{
		return self::render();
	}


}

