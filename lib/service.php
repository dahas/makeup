<?php

namespace makeup\lib;


/**
 * Class Service
 * @package makeup\lib
 */
abstract class Service
{
	protected $DB = null;
	private $recordset = null;
	
	private $table = "";
	private $columns = "*";
	private $where = [];


	/**
	 * 
	 * @param String $table Name of the table
	 * @param String $columns Comma-separaated list of columns (optional, default is *)
	 * @param String $where Where clause, optional
	 */
	public function __construct($table = "", $columns = "*", $where = "")
	{
		// Get the database instance
		$this->DB = DB::getInstance();

		$this->table = $table;
		$this->columns = $columns;
		$this->where = $where;
	}


	/**
	 * Creates the recordset from the basic setup of the service. 
	 * @return int $count
	 */
	public function useService($recordset = null)
	{
		if ($recordset) {
			$this->recordset = $recordset;
		} else {
			$statement = [
				"columns" => $this->columns,
				"from" => $this->table
			];
			if ($this->where) {
				$statement = array_merge($statement, ["where" => $this->where]);
			}
			$this->recordset = $this->DB->select($statement);
		}
		return $this->count();
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
	
	
	public function getByKey($key, $value)
	{
		
	}
	
	/**
	 * Creates the model of the data provided by the service.
	 * Cannot be executed before useService() has been run.
	 * @return mixed
	 * @throws \Exception
	 */
	public function next()
	{
		if (!$this->recordset) {
			throw new \Exception('No collection found! Run method "useService()" first.');
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

