<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-L.txt
 *
 * @category   AW
 * @package    Den_Stock
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Stock_Model_Route extends Mage_Core_Model_Config_Data{
	
	public function toOptionArray(){
        $options = array();
		return $options;
    }
	
	protected function _beforeSave(){
		$value = $this->getValue();
		if (trim($value) == "") {
			$value = "stock";
		}
		$this->setValue($value);
		return $this;
	}
}
