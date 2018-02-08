<?php

namespace makeup\services;

use makeup\lib\Service;
use makeup\lib\ServiceItem;


/**
 * Collection of members
 * @package makeup\services
 */
class Members extends Service
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
 * Single item.
 *
 * Class DataItem
 * @package makeup\services
 */
class MembersItem extends ServiceItem
{
	
}

