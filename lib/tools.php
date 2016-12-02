<?php

namespace makeup\lib;


/**
 * This class contains several useful and necessary functions.
 * Some of them are only used by the framework itself.
 *
 * Class Tools
 * @package makeup\lib
 */
class Tools
{
	private static $bodyOnload = '';


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


	/**
	 * @param $value
	 */
	public static function setBodyOnload($value)
	{
		self::$bodyOnload .= $value;
	}


	/**
	 * @return string
	 */
	public static function getBodyOnload()
	{
		return self::$bodyOnload;
	}


	/**
	 * @param $input
	 * @return mixed
	 */
	private static function filterInput($input)
	{
		return filter_var(rawurldecode($input), FILTER_SANITIZE_STRING);
	}


	/**
	 * @param $msg
	 * @return string
	 */
	public static function errorMessage($msg)
	{
		return '<span style="font-size: 12px; font-weight: bold; color: red;">' . $msg . '</span>';
	}


	/**
	 * @param $input
	 * @param string $separator
	 * @return mixed
	 */
	public static function upperCamelCase($input, $separator = '_')
	{
		return str_replace($separator, '', ucwords($input, $separator));
	}


	/**
	 * @param $input
	 * @param string $separator
	 * @return mixed
	 */
	public static function lowerCamelCase($input, $separator = '_')
	{
		str_replace($separator, '', lcfirst(ucwords($input, $separator)));
	}


	/**
	 * @param $input
	 * @return string
	 */
	public static function camelCaseToUnderscore($input)
	{
		return strtolower(preg_replace('/(?<!^)[A-Z]+/', '_$0', $input));
	}


	/**
	 * Merge 2 arrays
	 *
	 * @param $array1 appConfig
	 * @param $array2 modConfig
	 * @return mixed
	 */
	public static function arrayMerge($array1, $array2)
	{
		foreach ($array2 as $key => $val) {
			if (!is_array($val) && $val) {
				if (is_numeric($key))
					$array1[] = $val;
				else
					$array1[$key] = $val;
			} elseif (isset($array1[$key]) && is_array($val)) {
				$array1[$key] = self::arrayMerge($array1[$key], $val);
			} elseif (!isset($array1[$key])) {
				if (is_numeric($key))
					$array1[] = $array2[$key];
				else
					$array1[$key] = $array2[$key];
			}
		}
		return $array1;
	}


	/**
	 * Debug output in an iframe
	 * @param type $val
	 */
	public static function debug($val)
	{
		$_SESSION['_debug'] = $val;
		if (Config::get("app_settings", "dev_mode")) {
			$html = '<div style="position:fixed; bottom:0; left:70%; width:30%; z-index:99999; background: silver; border: 1px solid grey; padding:10px;">
							<iframe src="/makeup/lib/div/debug.php" style="width: 100%; height: 700px; border:none;"></iframe>
						</div>';
			echo $html;
		}
	}


}

