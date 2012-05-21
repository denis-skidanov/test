<?php
class Den_Staff_Model_System_Config_Source_Categories
{
    public function toOptionArray(){

        $categories = array();
	  	$collection = Mage::getModel('staff/cat')->getCollection()->setOrder('sort_order', 'asc');
		foreach ($collection as $cat) {
			$categories[] = ( array(
				'label' => (string)$cat->getTitle(),
				'value' => $cat->getCatId()
				));
		}
        return $categories;
    }
}