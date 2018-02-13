<?php

namespace makeup\lib;

/**
 * Class Session
 * @package makeup\lib
 */
class RQ
{
	private static $get = array();
	private static $post = array();
	
	public static function init($currentModule)
	{
		self::$get = self::parseQueryString();
		self::$post = self::parseFormData();

		if ($currentModule == self::$get["mod"]) {
			unset($_GET);
			$_GET = null;
			
			unset($_POST);
			$_POST = null;
		}
	
	}

	/**
	 * Value of a query parameter
	 * 
	 * @param string $key
	 * @return string $value
	 */
	public static function GET($key)
	{
		return isset(self::$get[$key]) ? self::$get[$key] : null;
	}

	/**
	 * Value of a formular
	 * 
	 * @param string $key
	 * @return string $value
	 */
	public static function POST($key)
	{
		return isset(self::$post[$key]) ? self::$post[$key] : null;
	}

	public static function parseQueryString()
	{
		$varArr = array();
		if (isset($_SERVER['QUERY_STRING'])) {
			$qs = $_SERVER['QUERY_STRING'];
			$vars = explode("&", $qs);
			foreach ($vars as $var) {
				$parts = explode("=", $var);
				if (count($parts) == 2)
					$varArr[$parts[0]] = self::filterInput($parts[1]);
				else
					$varArr[$parts[0]] = "";
			}
		}

		// Parameters "mod" and "task" are always required!
		if (!isset($varArr["mod"]))
			$varArr["mod"] = Config::get("app_settings", "default_module");

		if (!isset($varArr["task"]))
			$varArr["task"] = "build";

		return $varArr;
	}

	public static function parseFormData()
	{
		$varArr = array();
		if (isset($_POST)) {
			foreach ($_POST as $post => $var) {
				$varArr[$post] = self::filterInput($var);
			}
		}

		return $varArr;
	}

	/**
	 * @param $input
	 * @return mixed
	 */
	private static function filterInput($input)
	{
		return filter_var(rawurldecode($input), FILTER_SANITIZE_STRING);
	}

}

