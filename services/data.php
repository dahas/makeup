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
	public function __construct()
	{
		/**
		 * Call parent constructor and provide some options.
		 */
		parent::__construct(
			"data", // Name of the table
			"name, age, city"	// Required columns (optional, default is *.)
		);
	}


	/**
	 * Function with a more complex SQL statement. If you have defined
	 * a setup before a custom select, the setup will be overwritten.
	 * @param type $name	Parameter for where clause.
	 */
	public function selectUsers($name)
	{
		$rs = $this->DB->select([
			"columns" => "*",
			"from" => "data",
			"where" => "name='$name'"
		]);
		\makeup\lib\Tools::debug($rs);
		return $this->useService($rs);
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

