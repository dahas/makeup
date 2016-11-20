<?php

namespace makeup\lib;

use DI\ContainerBuilder;


/**
 * Abstract Class Module
 * @package makeup\lib\interfaces
 */
abstract class Module
{
    protected $resFolder = "makeup/app/res";

    protected $Template = null;

    protected $_get = array();

    protected $config = array();


    public function __construct()
    {
        $modNsArr = explode("\\", get_class($this));
        $className = array_pop($modNsArr);
        $moduleFileName = Tools::camelCaseToUnderscore($className);

        Config::init($moduleFileName); // Loads config.ini

        $this->_get = Tools::parseQueryString();

        if ($moduleFileName == "app")
            $this->Template = Template::load("makeup/app/view/app.html");
        else
            $this->Template = Template::load("makeup/modules/$moduleFileName/view/$moduleFileName.html");
    }


    /**
     * @return object
     * @throws exception
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
            throw new exception('Not a valid classname!');
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


    abstract public function render();


    /**
     * @param $method
     * @param $args
     * @return string
     */
    public function __call($method, $args)
    {
        return Tools::errorMessage("Method $method() not defined!");
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
}