<?php

namespace makeup\lib;


/**
 * Class Service
 * @package makeup\lib
 */
abstract class Service
{
	protected $DB = null;
	protected $recordset = null;
	
	private $table = "";
	private $columns = "*";


	public function __construct()
	{
		// Call the database instance
		$this->DB = DB::getInstance();
		
		$config = $this->srvSetup();
		
		if (isset($config["table"]))
			$this->table = $config["table"];
		
		if (isset($config["columns"]))
			$this->columns = $config["columns"];
	}
	
	
	protected function srvSetup() {}
	
	/**
	 * In this function the select statement has to be defined.
	 */
	public function getRecordset()
	{
		$this->recordset = $this->DB->select([
				"columns" => $this->columns,
				"from" => $this->table
		]);
	}


	/**
	 * In this function the statement with a where clause has to be defined.
	 * @param type $key			Name of the column
	 * @param type $value		Value of the column
	 * @param type $fields	Which fields to return (optional)
	 */
	public function useRecord($key, $value, $fields = "")
	{
		$this->recordset = $this->getRecordByKey($key, $value, $fields = "");
		return $this->getRecord();
	}


	/**
	 * This function is more an alias. The name might be easier 
	 * to understand when implementing the service.
	 * @return int RecordCount
	 */
	public function count()
	{
		return $this->recordset->getRecordCount();
	}


	/**
	 * Creates the model of the data provided by the service.
	 * Cannot be executed before useService() has been run.
	 * @return mixed
	 * @throws \Exception
	 */
	public function getRecord()
	{
		if (!$this->recordset) {
			throw new \Exception('No collection found! Run method "getRecordset()" first.');
		}

		if ($record = $this->recordset->getRecord()) {
			// Getting name of child class (our service)
			$serviceClass = get_class($this) . "Item";
			return new $serviceClass($record);
		}

		$this->recordset->reset();

		return null;
	}


}

