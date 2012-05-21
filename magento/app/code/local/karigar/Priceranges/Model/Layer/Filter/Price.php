<?php

/*
 * @auther FaisalAbbas (Karigar Web Solutions)
 * @descriptio ()
 */


/**
     * Retrieve resource instance
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Price
     */
class  Karigar_Priceranges_Model_Layer_Filter_Price extends Mage_Catalog_Model_Layer_Filter_Price
{
	 /**
     * Retrieve resource instance
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Price
     */
	
	 /**
     * Prepare text of item label
     *
     * @param   int $range
     * @param   float $value
     * @return  string
     */
    protected function _renderItemLabel($range, $value)
    {
        $store      = Mage::app()->getStore();
        $fromPrice  = $store->formatPrice($value);
        $toPrice    = $store->formatPrice($range);

        return Mage::helper('catalog')->__('%s - %s', $fromPrice, $toPrice);
    }

}
   

?>
