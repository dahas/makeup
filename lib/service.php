<?php

namespace MakeUp\lib;

/**
 * Class Service
 * @package MakeUp\lib
 */
abstract class Service
{
    protected $DB = null;

    protected $recordset = null;


    public function __construct()
    {
        // Call the database instance
        $this->DB = DB::getInstance();
    }


    /**
     * In this function the select statement has to be defined.
     */
    protected function getCollection() {}


    /**
     * This function is more an alias. The name might be easier
     * to understand when implementing the service.
     */
    public function useService()
    {
        $this->recordset = $this->getCollection();
    }


    /**
     * Creates the model of the data provided by the service.
     * Cannot be executed before useService() has been run.
     * @return mixed
     * @throws \Exception
     */
    public function getItem()
    {
        if(!$this->recordset) {
            throw new \Exception('No Service found! Run method "useService()" first.');
        }

        if ($record = $this->recordset->getRecord())
        {
            // Getting name of child class (our service)
            $serviceClass = get_class($this) . "Item";
            return new $serviceClass($record);
        }

        $this->recordset->reset();

        return null;
    }
}