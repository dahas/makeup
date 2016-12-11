<?php

namespace makeup\services;

use makeup\lib\Service;
use makeup\lib\ServiceItem;


/**
 * Class Data
 * @package makeup\services
 */
class Data extends Service
{
	public function __construct($key = "", $value = "", $fields = "")
	{
		parent::__construct($key, $value, $fields);
	}


	/**
	 * Which columns of which table should be used in the recordset. If no columns 
	 * are defined, all columns [*] will be used by defaault.
	 * @return type
	 */
	public function setupService()
	{
		return [
			"table" => "data",
			"columns" => "name, age, city"
		];
	}


}


/**
 * Name this class according to the service but add "Item" as suffix.
 *
 * Class DataItem
 * @package makeup\services
 */
class DataItem extends ServiceItem
{
	
}

