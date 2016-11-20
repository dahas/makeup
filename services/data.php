<?php

namespace MakeUp\services;

use MakeUp\lib\Service;
use MakeUp\lib\ServiceItem;


/**
 * Class Data
 * @package MakeUp\services
 */
class Data extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getCollection()
    {
        return $this->DB->select([
            "columns" => "*",
            "from" => "data"
        ]);
    }
}


/**
 * Name this class according to the service but add "Item" as suffix.
 *
 * Class DataItem
 * @package MakeUp\services
 */
class DataItem extends ServiceItem
{

}
