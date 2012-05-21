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
 * @package    Den_Job
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Job_Model_Dateformat{
    protected $_options;
	const FORMAT_TYPE_FULL  = 'full';
    const FORMAT_TYPE_LONG  = 'long';
    const FORMAT_TYPE_MEDIUM= 'medium';
    const FORMAT_TYPE_SHORT = 'short';
    
    public function toOptionArray(){
        if (!$this->_options) {
			$this->_options[] = array(
			   'value'=>self::FORMAT_TYPE_FULL,
			   'label'=>Mage::helper('job')->__('Full')
			);
			$this->_options[] = array(
			   'value'=>self::FORMAT_TYPE_LONG,
			   'label'=>Mage::helper('job')->__('Long')
			);
			$this->_options[] = array(
			   'value'=>self::FORMAT_TYPE_MEDIUM,
			   'label'=>Mage::helper('job')->__('Medium')
			);
			$this->_options[] = array(
			   'value'=>self::FORMAT_TYPE_SHORT,
			   'label'=>Mage::helper('job')->__('Short')
			);
		}
		return $this->_options;
	}
}
