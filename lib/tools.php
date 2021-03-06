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
	
	private static $debugArr = [];
	
	
	/**
	 * Loads an ini file. Either the one that belongs to the module, 
	 * or a special one. If it doesn´t find a file
	 * @param type $modName
	 * @param string $fileName
	 * @return type
	 */
	public static function loadIniFile($modName = "app", $fileName = "")
	{
		if (!$fileName)
			$fileName = $modName . ".ini";
	
		if (strtolower($modName) == "app")
			$path = "makeup/app/config/app.ini";
		else
			$path = "makeup/modules/$modName/config/$fileName";
		
		if(file_exists($path))
			return parse_ini_file($path, true);
		else
			return null;
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
		return str_replace($separator, '', lcfirst(ucwords($input, $separator)));
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
	public static function debug($val="")
	{
		$bt = debug_backtrace();
		$caller = array_shift($bt);
		unset($caller["function"]);
		unset($caller["class"]);
		unset($caller["type"]);
		self::$debugArr[] = $caller;
		Session::set('_debug', self::$debugArr);
	}


	/**
	 * Debug output in an iframe
	 * @param type $val
	 */
	public static function renderDebugPanel()
	{
		if (SysCookie::get("panel_open") == true) {
			$dbgHandleIcon = "fa-times";
			$dbgHandleDspl = "block";
		} else {
			$dbgHandleIcon = "fa-chevron-left";
			$dbgHandleDspl = "none";
		}
		if (Config::get("app_settings", "dev_mode")) {
			$height = Session::get('_debug') ? 700 : 377;
			$html = '<div style="position:fixed; bottom:0; right:0; z-index:99999; background: silver; border: 1px solid grey;">
  <div id="dbg-handle" style="float:left; width: 20px; padding:2px 2px 0 4px; cursor: pointer;" title="Debug panel"><i class="fa '.$dbgHandleIcon.'"> </i></div>
  <div id="dbg-frame" style="display:'.$dbgHandleDspl.'; float:right; width:500px;">
    <iframe src="/makeup/lib/div/debug.php" style="width: 100%; height: '.$height.'px; border:none;"></iframe>
  </div>
</div>';
			return $html;
		}
	}


}

