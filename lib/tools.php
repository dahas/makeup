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
			$varArr["mod"] = "";

		if (!isset($varArr["task"]))
			$varArr["task"] = "render";

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
				$array1[] = $val;
			} elseif (isset($array1[$key]) && is_array($val)) {
				$array1[$key] = self::arrayMerge($array1[$key], $val);
			} elseif (!isset($array1[$key])) {
				$array1[$key] = $array2[$key];
			}
		}
		return $array1;
	}


	/**
	 * Debug output
	 * @param type $val
	 */
	public static function debug($val)
	{
		echo '<div style="position:fixed; bottom:0; left:70%; width:30%; max-height:100%; z-index:99999; border: 1px solid grey; background: silver; padding:10px;">';
		echo '<div style="overflow:scroll; height:700px;">';
		echo '<pre>';
		print_r($val);
		echo '</pre></div></div>';
	}


}

