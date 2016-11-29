<?php

use makeup\lib\Module;
use makeup\lib\Template;


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
     */
    private $Data;


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
    public function render()
    {
        /**
         * Always run useService() first, so that the service is available.
         * NOTE: The service object isn´t available in the constructor. Thus you
         * cannot run it there.
         */
        $this->Data->useService();

        /**
         * Get a supart of the template like this and treat it like a template.
         */
        $spMarkerString = "%SUBPART_ROWS%";
        $subpart = $this->getTemplate()->getSubpart($spMarkerString);
        $spMarker[$spMarkerString] = "";

        // Iterate with getItem() thru the data that the service provides.
        while($dataItem = $this->Data->getItem()) {
            $spMarker[$spMarkerString].= $subpart->parse([
                "%UID%" => $dataItem->getProperty("uid"),   // Use getProperty(item) to get the value of a specific item.
                "%NAME%" => $dataItem->getProperty("name"),
                "%AGE%" => $dataItem->getProperty("age"),
                "%CITY%" => $dataItem->getProperty("city"),
                "%COUNTRY%" => $dataItem->getProperty("country")
            ]);
        }

        /**
         * Or get an extra template file for the supart.
         */
        $markerString = "%TMPL_ROWS%";
        $partial = Template::load(__CLASS__, "twbs_table_row.html");
        $marker[$markerString] = "";

        // Iterate with getItem() thru the data that the service provides.
        while($dataItem = $this->Data->getItem()) {
            $marker[$markerString].= $partial->parse([
                "%UID%" => $dataItem->getProperty("uid"),   // Use getProperty(item) to get the value of a specific item.
                "%NAME%" => $dataItem->getProperty("name"),
                "%AGE%" => $dataItem->getProperty("age"),
                "%CITY%" => $dataItem->getProperty("city"),
                "%COUNTRY%" => $dataItem->getProperty("country")
            ]);
        }

        return $this->getTemplate()->parse($marker, $spMarker);
    }
}
