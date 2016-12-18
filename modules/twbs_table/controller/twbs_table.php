<?php

use makeup\lib\Module;
use makeup\lib\Template;
use makeup\lib\Tools;


/**
 * The name of a modules class must always be UpperCamelCase.
 * But when you create a module in another context, you must use the name of the
 * class file (without the extension ".php")!
 *
 * Class TwbsTable
 */
class TwbsTable extends Module
{
	/**
	 * This is how you inject a service.
	 *
	 * @Inject("makeup\services\Data")
	 * @var
	 */
	private $DataService;


	/**
	 * Calling the parent constructor is required!
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * @return mixed|string
	 */
	public function build()
	{
		// Get a supart of the template like this and treat it like a template.
		$subpart = $this->getTemplate()->getPartial("%SUBPART_ROWS%");
		$spMarker["%SUBPART_ROWS%"] = "";

		// Or get an extra template file for the supart.
		$partial = $this->getTemplate("twbs_table_row.html");
		$marker["%TMPL_ROWS%"] = "";
		
		// Get the basic data.
		$count = $this->DataService->useService();
		
		Tools::debug("count = $count");

		// Iterate with next() thru the data that the service provides.
		while ($dataItem = $this->DataService->next()) {
			$mkArr = [
				"%UID%" => $dataItem->getProperty("uid"), // With getProperty(item) you get the value of a specific item.
				"%NAME%" => $dataItem->getProperty("name"),
				"%AGE%" => $dataItem->getProperty("age"),
				"%CITY%" => $dataItem->getProperty("city"),
				"%COUNTRY%" => $dataItem->getProperty("country")
			];
			$spMarker["%SUBPART_ROWS%"] .= $subpart->parse($mkArr);
			$marker["%TMPL_ROWS%"] .= $partial->parse($mkArr);
		}

		return $this->getTemplate()->parse($marker, $spMarker);
	}


}

