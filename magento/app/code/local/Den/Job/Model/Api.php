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
class Den_Job_Model_Api extends Mage_Core_Model_Abstract {

    public function _construct() {

        parent::_construct();
        $this->_init('job/job');
    }

    public function getPostUrl($id) {

        $post = $this->load($id);

        if ($post->getId()) {

            $route = Mage::helper('job')->getRoute();
            return Mage::getUrl("{$route}/{$post->getIdentifier()}");
        }

        return false;
    }

    public function getPostCategories($id) {

        return Mage::getModel('job/cat')
                        ->getCollection()
                        ->addStoreFilter(Mage::app()->getStore()->getId())
                        ->addPostFilter($id);
    }

    public function getPosts($status = array(),$store = array()) {
         
        $collection = Mage::getModel('job/post')->getCollection();
         
        if(is_array($store) && !empty($store)) {
              $collection->addStoreFilter($store);
        }

        if (!empty($status)) {
            $collection->addStatusFilter($status);
        } else {
            $collection->addStatusFilter();
        }

        return $collection;
    }
    
    public function getPostShortContent($post,$storeId = 0) { 
        
        $content = $post->getPostContent();
        
        if (Mage::getStoreConfig(Den_Job_Helper_Config::XML_BLOG_USESHORTCONTENT,$storeId) && trim($post->getShortContent())) {             
            
                $content = trim($post->getShortContent());               
                 
            } elseif ((int) Mage::getStoreConfig(Den_Job_Helper_Config::XML_BLOG_READMORE, $storeId)) {
 
                $strManager = new Den_Job_Helper_Substring(array('input' => Mage::helper('job')->filterWYS($post->getPostContent())));
                $content = $strManager->getHtmlSubstr((int) Mage::getStoreConfig(Den_Job_Helper_Config::XML_BLOG_READMORE));
 
            }
            
            return $content;
         
    }
    
    

}
