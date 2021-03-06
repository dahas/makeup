<?php

namespace makeup\lib;

/**
 * Class Session
 * @package makeup\lib
 */
class RQ
{
	public static function init()
	{
		$_GET = self::parseQueryString();
		$_POST = self::parseFormData();
	}

	/**
	 * Value of a query parameter
	 * 
	 * @param string $key
	 * @return string $value
	 */
	public static function GET($key)
	{
		return isset($_GET[$key]) ? $_GET[$key] : null;
	}

	/**
	 * Value of a formular
	 * 
	 * @param string $key
	 * @return string $value
	 */
	public static function POST($key)
	{
		return isset($_POST[$key]) ? $_POST[$key] : null;
	}

	public static function parseQueryString()
	{
		$varArr = array_map('self::filterInput', $_GET);

		// Parameters "mod" and "task" are mandatory!
		if (!isset($varArr["mod"]))
			$varArr["mod"] = Config::get("app_settings", "default_module");

		if (!isset($varArr["task"]))
			$varArr["task"] = "build";

		return $varArr;
	}

	public static function parseFormData()
	{
		return array_map('self::filterInput', $_POST);
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

