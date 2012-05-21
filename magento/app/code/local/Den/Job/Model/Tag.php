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

class Den_Job_Model_Tag extends Mage_Core_Model_Abstract{

    protected function _construct(){
        $this->_init('job/tag');
    }
    
    public function refreshCount($store = null){
        //Refreshes tag count
        $postsCount = Mage::getModel('job/job')
            ->getCollection();
            if($store){    
                $postsCount->addStoreFilter($store);
            }
            $postsCount = $postsCount->addTagFilter($this->getTag())
            ->count();
            //var_dump($postsCount);die();
        
        
        $this->setTagCount($postsCount)->save();
        return $this;
    }
    
    public function loadByName($name, $store = null){
        $coll = Mage::getModel('job/tag')->getCollection();
        
        $sel = $coll->getSelect();
        
        $coll->getSelect()
            ->where('tag=?',$name);
        if(!Mage::app()->isSingleStoreMode() && !is_null($store)){
            $coll->getSelect()->where('store_id=?', $store);
        }
        
        
        foreach($coll->load() as $item){
            return $item;
        }
        
        if(!is_null($store)){
            $this->setStoreId($store);
        }
        return $this->setTag($name);    
    }

}