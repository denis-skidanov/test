<?php
/**
 * Flagbit_FactFinder
 *
 * @category  Mage
 * @package   Flagbit_FactFinder
 * @copyright Copyright (c) 2010 Flagbit GmbH & Co. KG (http://www.flagbit.de/)
 */

/**
 * Ressource Model class
 *
 * Queue for SCIC orders. Orders are sent to FACT-Finder asynchronously by cronjobs.
 *
 * @category  Mage
 * @package   Flagbit_FactFinder
 * @copyright Copyright (c) 2010 Flagbit GmbH & Co. KG (http://www.flagbit.de/)
 * @author    Michael Türk <tuerk@flagbit.de>
 * @version   $Id: Processor.php 647 2011-03-21 10:32:14Z rudolf_batt $
 */
class Flagbit_FactFinder_Model_Mysql4_Scic_Queue extends Mage_Core_Model_Mysql4_Abstract
{

    /**
     * Constructor with simple Magento initialisation
     */
    protected function _construct() {
        $this->_init('factfinder/scic_queue', 'id');
    }

}