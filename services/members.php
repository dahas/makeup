<?php

namespace makeup\services;

use makeup\lib\Service;
use makeup\lib\ServiceItem;


/**
 * Collection of members
 * 
 * @package makeup\services
 */
class Members extends Service
{
	public function __construct()
	{
		/**
         * IMPORTANT: Modify the constructor first.
         * Supply the table name and optionally the unique column that increases automatically and the specific columns.
         */
        parent::__construct(
            "data",
			"uid",
			"name, age, city, country"
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

