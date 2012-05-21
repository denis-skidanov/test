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
 * @package    Den_Staff
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Staff_Model_Dateformat{
    protected $_options;
	const FORMAT_TYPE_FULL  = 'full';
    const FORMAT_TYPE_LONG  = 'long';
    const FORMAT_TYPE_MEDIUM= 'medium';
    const FORMAT_TYPE_SHORT = 'short';
    
    public function toOptionArray(){
        if (!$this->_options) {
			$this->_options[] = array(
			   'value'=>self::FORMAT_TYPE_FULL,
			   'label'=>Mage::helper('staff')->__('Full')
			);
			$this->_options[] = array(
			   'value'=>self::FORMAT_TYPE_LONG,
			   'label'=>Mage::helper('staff')->__('Long')
			);
			$this->_options[] = array(
			   'value'=>self::FORMAT_TYPE_MEDIUM,
			   'label'=>Mage::helper('staff')->__('Medium')
			);
			$this->_options[] = array(
			   'value'=>self::FORMAT_TYPE_SHORT,
			   'label'=>Mage::helper('staff')->__('Short')
			);
		}
		return $this->_options;
	}
}
