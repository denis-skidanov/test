<?php

/*
 * @auther FaisalAbbas (Karigar Web Solutions)
 * @descriptio ()
 */
class Karigar_Priceranges_Model_Resource_Layer_Filter_Price extends Mage_Catalog_Model_Resource_Layer_Filter_Price
{
	/**
     * Apply attribute filter to product collection
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param int $to
     * @param int $from    the range factor
     * @return Mage_Catalog_Model_Resource_Layer_Filter_Price
     */
	public function applyFilterToCollection($filter, $to ,$from)
    {
        $collection = $filter->getLayer()->getProductCollection();
        $collection->addPriceData($filter->getCustomerGroupId(), $filter->getWebsiteId());

        $select     = $collection->getSelect();
        $response   = $this->_dispatchPreparePriceEvent($filter, $select);

        $table       = $this->_getIndexTableAlias();
        $additional  = join('', $response->getAdditionalCalculations());
        $rate        = $filter->getCurrencyRate();
		$priceExpr   = new Zend_Db_Expr("(({$table}.min_price {$additional}) * {$rate})");
		$condition   = $priceExpr . " between ". $from." and ".$to ;
	    $select->where($condition);
        return $this;
    }
}
?>
