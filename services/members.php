<?php

namespace makeup\services;

use makeup\lib\Service;
use makeup\lib\ServiceItem;


/**
 * Class members
 * @package makeup\services
 */
class members extends Service
{
	public function __construct()
	{
		/**
		 * Call of parent constructor is mandatory.
		 * 
		 * @param string $table Name of the table
		 * @param string $columns Comma-separated list of columns (optional, default is *)
		 */
		parent::__construct(
			"data",
			"uid, name, age, city, country"
		);
	}

}


/**
 * Name this class according to the service but add "Item" as suffix.
 *
 * Class DataItem
 * @package makeup\services
 */
class membersItem extends ServiceItem
{
	
}

