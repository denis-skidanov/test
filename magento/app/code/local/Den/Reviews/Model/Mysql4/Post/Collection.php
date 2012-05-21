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
 * @package    Den_Reviews
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Reviews_Model_Mysql4_Post_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_previewFlag;

    protected function _construct()
    {
        $this->_init('reviews/reviews');
	
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('identifier', 'title');
    }
	
	public function addStoreFilter($store) {
        
        if (!Mage::app()->isSingleStoreMode()) {

            if ($store instanceof Mage_Core_Model_Store) {
                $store = $store->getId();
            }

            $store = (array) $store;
            array_push($store,0);

            $this->getSelect()
                  ->distinct()
                  ->join(array('store_table' => $this->getTable('store')), 'main_table.post_id = store_table.post_id', array())
                  ->where('store_table.store_id in (?)', array($store));
                     
        }

        return $this;
    }
    
    public function addStatusFilter($status = array(Den_Reviews_Model_Status::STATUS_ENABLED,Den_Reviews_Model_Status::STATUS_HIDDEN)) {
       
        if($status == '*') { $status = array(Den_Reviews_Model_Status::STATUS_ENABLED,Den_Reviews_Model_Status::STATUS_HIDDEN,Den_Reviews_Model_Status::STATUS_DISABLED); }
       
        if(is_string($status)) { $status = (array) $status; }
        
        $this->getSelect()->where('main_table.status IN (?)',$status);
        
        return $this;
     
    }
    
    

}
