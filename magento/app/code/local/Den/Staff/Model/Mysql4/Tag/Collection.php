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

class Den_Staff_Model_Mysql4_Tag_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_previewFlag;

    protected function _construct()
    {
        $this->_init('staff/tag');
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('identifier', 'title');
    }
    
    public function addStoreFilter($store)
    {
        if (!Mage::app()->isSingleStoreMode()){
            $id = $store->getId();
            $this->getSelect()->where('store_id='.$id.' OR store_id=0');
            return $this;
        }
        return $this;
    }
    
    public function addTagFilter($tag){
        $this->getSelect()->where('tag=?', $tag);
            return $this;
    }
}
