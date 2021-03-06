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

class Den_Staff_Block_Tags extends Mage_Core_Block_Template {


    public function _construct(){
        parent::_construct();
        return $this->setTemplate('aw_staff/tags.phtml');
    }
    
    public function getCollection(){
        if($this->getData('collection')){
            return $this->getData('collection');
        }    
        $coll = Mage::getModel('staff/tag')->getCollection(); 
        $coll->getSelect()
                ->where('tag_count > 0')
                ->columns(array('tag_final_count'=>'SUM(tag_count)'))
                ->joinLeft(array("stores"=>$coll->getTable('staff/store')),'main_table.store_id = stores.store_id',array())
                ->joinLeft(array("staffs"=>$coll->getTable('staff/staff')),"stores.post_id = staffs.post_id",array())
                ->where('staffs.status = 1')
                ->where('FIND_IN_SET(main_table.tag, staffs.tags)')
                ->where('main_table.store_id = ? OR main_table.store_id = 0', Mage::app()->getStore()->getId())
                ->order(array('tag_final_count DESC',
                           'tag'))
                           
                ->limit(Mage::getStoreConfig(Den_Staff_Helper_Config::XML_TAGCLOUD_SIZE))
                ->group('tag');

        foreach($coll as $item){
            if($item->getTagFinalCount() >= $this->getMaxCount()){
                $this->setMaxCount($item->getTagFinalCount());
            }elseif($item->getTagFinalCount() <= $this->getMinCount() || ! $this->getMinCount()){
                $this->setMinCount($item->getTagFinalCount());
                $this->setMinTag($item);
            }


        }
        if($coll->count()){
            if(!$this->getMinTag()){
                $this->setMinTag($item);
            }
            if(!$this->getMaxTag()){
                $this->setMaxTag($item);
            }
        }        
        
        $this->setCollection($coll);    
        return $this->getCollection();               
    }
    
    public function getTagWeight($tag, $isMin=null){
        
        
        /* Returns tag weight from 1 to 10, starts from 1 */
        $total_results = $this->getCollection()->count();
        $min_weight = $this->getMinCount();
        $max_weight = $this->getMaxCount();
        
        $count = $tag->getTagFinalCount();
        
        if($max_weight){
            $k = ($count/(intval($max_weight)));
        }else{
            $k = 0.1;
        }
        
        if(!$isMin){
            $weight = $this->getTagWeight($this->getMinTag(),1);
            if((int)$weight){
                $k = $k / $weight;
            }else{
                $k = 0.1;
            }
        }
        
        return round($k * 10);
    }
    
    public function getTagHref($tag){

        $route = Mage::helper('staff')->getRoute();
        return Mage::getUrl($route."/tag/".urlencode($tag->getTag()));
    }
   
   
}
