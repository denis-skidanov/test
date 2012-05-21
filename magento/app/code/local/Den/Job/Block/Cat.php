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
class Den_Job_Block_Cat extends Den_Job_Block_Abstract {
     
    public function getPosts() {
        
        $cats = Mage::getSingleton('job/cat');
       
        if ($cats->getCatId() === NULL) {
            return false;
        } 
        
          $collection = parent::_prepareCollection(array('addCatFilter'=>$cats->getCatId()));            
          parent::_processCollection($collection,$categoryMode = true);    
         
           return $collection;
       
    }
  
    public function getCat() {
        $cats = Mage::getSingleton('job/cat');
        return $cats;
    }

    public function getPages() {
        
        echo parent::getPagesCollection('category',array('setCatId'=>$this->getCat()->getId()));
        
    }
    

    protected function _prepareLayout() {
        
        $post = $this->getCat();
        
        $route = Mage::helper('job')->getRoute();

        // show breadcrumbs
        if (Mage::getStoreConfig('job/job/jobcrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', array('label' => Mage::helper('job')->__('Home'), 'title' => Mage::helper('job')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
            ;
            $breadcrumbs->addCrumb('job', array('label' => Mage::getStoreConfig('job/job/title'), 'title' => Mage::helper('job')->__('Return to ' . Mage::getStoreConfig('job/job/title')), 'link' => Mage::getUrl($route)));
            $breadcrumbs->addCrumb('job_page', array('label' => $post->getTitle(), 'title' => $post->getTitle()));
        }

        if ($head = $this->getLayout()->getBlock('head')) {           
            $head->setTitle($post->getTitle());
            $head->setKeywords($post->getMetaKeywords());
            $head->setDescription($post->getMetaDescription());
        }
    }
    
    
    protected function _toHtml() {
        return Mage::helper('job')->filterWYS(parent::_toHtml());
    }

 

}
