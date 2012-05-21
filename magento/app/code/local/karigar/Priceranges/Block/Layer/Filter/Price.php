<?php

/*
 * @auther FaisalAbbas (Karigar Web Solutions)
 * @descriptio ()
 */
class  Karigar_Priceranges_Block_Layer_Filter_Price extends Mage_Catalog_Block_Layer_Filter_Price
{
	public function __construct()
    {
        parent::__construct();

        $this->_filterModelName = 'catalog/layer_filter_price';
		$this->setTemplate('priceranges/layer/price_filter.phtml');
    }
	
	public function getMaxPriceInt() {
		return Mage::getModel($this->_filterModelName)->getMaxPriceInt();
	}
	
}
?>
