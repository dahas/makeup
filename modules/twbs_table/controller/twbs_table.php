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
	 * @Inject("makeup\services\members")
	 * @var
	 */
	private $members;


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
		$subpart = $this->getTemplate()->getPartial("##SUBPART_ROWS##");
		$spMarker["##SUBPART_ROWS##"] = "";

		// Or get an extra template file for the supart.
		$partial = $this->getTemplate("twbs_table_row.html");
		$marker["##TMPL_ROWS##"] = "";

		#$this->members->create("name, age, city, country", "Karl, 66, München, Germany");
		#$this->members->update("name='Klaus'", "uid=5");
		#$this->members->delete("uid=5");
		$member = $this->members->getByKey("uid", 2);
		$member->setProperty("name", "Gustavo");
		$member->update();
		#Tools::debug($member);
		
		// Get the data:
		$count = $this->members->read("age > 30");

		// Iterate with next() thru the data that the service provides.
		while ($dataItem = $this->members->next()) {
			$mkArr = [
				"##UID##" => $dataItem->getProperty("uid"), // With getProperty(item) you get the value of a specific item.
				"##NAME##" => $dataItem->getProperty("name"),
				"##AGE##" => $dataItem->getProperty("age"),
				"##CITY##" => $dataItem->getProperty("city"),
				"##COUNTRY##" => $dataItem->getProperty("country")
			];
			$spMarker["##SUBPART_ROWS##"] .= $subpart->parse($mkArr);
			$marker["##TMPL_ROWS##"] .= $partial->parse($mkArr);
		}

		return $this->getTemplate()->parse($marker, $spMarker);
	}


}

