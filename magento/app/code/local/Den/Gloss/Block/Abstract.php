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
 * @package    Den_Gloss
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */
class Den_Gloss_Block_Abstract extends Mage_Core_Block_Template {

    protected function _processCollection($collection, $category = false) {

        $route = Mage::helper('gloss')->getRoute();

        foreach ($collection as $item) {
           
            /* Escape tags */
            Den_Gloss_Helper_Data::escapeSpecialChars($item);
            
            
            if ($category) {                
                if (Mage::getStoreConfig('gloss/gloss/categories_urls')) {
                    $item->setAddress($this->getUrl($route . '/cat/' .  $this->getCat()->getIdentifier() . '/post/' . $item->getIdentifier()));
                } else {
                    $item->setAddress($this->getUrl($route . "/" . $item->getIdentifier()));
                }
            } else {
               $item->setAddress($this->getUrl($route . "/" . $item->getIdentifier()));
            }
 
            $item->setCreatedTime($this->formatTime($item->getCreatedTime(), Mage::getStoreConfig('gloss/gloss/dateformat'), true));
            $item->setUpdateTime($this->formatTime($item->getUpdateTime(), Mage::getStoreConfig('gloss/gloss/dateformat'), true));

            if (Mage::getStoreConfig(Den_Gloss_Helper_Config::XML_BLOG_USESHORTCONTENT) && trim($item->getShortContent())) {
                $content = trim($item->getShortContent());
                $content = $this->closetags($content);
                $content .= ' <a href="' . $this->getUrl($route . "/" . $item->getIdentifier()) . '" >' . $this->__('Read More') . '</a>';
                $item->setPostContent($content);
            } elseif ((int) Mage::getStoreConfig(Den_Gloss_Helper_Config::XML_BLOG_READMORE) != 0) {
                
                
                $content = $item->getPostContent();
                $strManager = new Den_Gloss_Helper_Substring(array('input' => Mage::helper('gloss')->filterWYS($content)));
                $content = $strManager->getHtmlSubstr((int) Mage::getStoreConfig(Den_Gloss_Helper_Config::XML_BLOG_READMORE));

                if ($strManager->getSymbolsCount() == Mage::getStoreConfig(Den_Gloss_Helper_Config::XML_BLOG_READMORE)) {
                    $content .= ' <a href="' . $this->getUrl($route . "/" . $item->getIdentifier()) . '" >' . $this->__('Read More') . '</a>';
                }
                $item->setPostContent($content);
            }


            $comments = Mage::getModel('gloss/comment')->getCollection()
                    ->addPostFilter($item->getPostId())
                    ->addApproveFilter(2);
            $item->setCommentCount(count($comments));

            $cats = Mage::getModel('gloss/cat')->getCollection()
                    ->addPostFilter($item->getPostId());
            $catUrls = array();
            foreach ($cats as $cat) {
                $catUrls[$cat->getTitle()] = Mage::getUrl($route . "/cat/" . $cat->getIdentifier());
            }


            $item->setCats($catUrls);
        }


        if ($category) {

            $this->setData('cat', $collection);
            return $this->getData('cat');
        }


        return $collection;
    }
     
    public function getBookmarkHtml($post) {
        if (Mage::getStoreConfig('gloss/gloss/bookmarkslist')) {
            $this->setTemplate('aw_gloss/bookmark.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getTagsHtml($post) {

        if (trim($post->getTags())) {
            $this->setTemplate('aw_gloss/line_tags.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getCommentsEnabled() {
        return Mage::getStoreConfig('gloss/comments/enabled');
    }
 
    public function getPagesCollection($mode,$params = array()) {
        
          if ((int) Mage::getStoreConfig('gloss/gloss/perpage') != 0) { 
             
            if($mode == 'list') { $bool = false; }
            else { $bool = true; }

            $pager = Mage::getConfig()->getBlockClassName('gloss/pager'); 
            $pager = new $pager();
            $pager->setTemplate('aw_gloss/pager/list.phtml');
            $pager->setCategoryMode($bool); 
            
            
            foreach($params as $key => $param) {                
                $pager->{$key}($param);                
            }
           
            return $pager->renderView();
         }
     
    }
 

    public function addTopLink() {
        if (Mage::helper('gloss')->getEnabled()) {
            $route = Mage::helper('gloss')->getRoute();
            $title = Mage::getStoreConfig('gloss/gloss/title');
            $this->getParentBlock()->addLink($title, $route, $title, true, array(), 15, null, 'class="top-link-gloss"');
        }
    }

    public function addFooterLink() {
        if (Mage::helper('gloss')->getEnabled()) {
            $route = Mage::helper('gloss')->getRoute();
            $title = Mage::getStoreConfig('gloss/gloss/title');
            $this->getParentBlock()->addLink($title, $route, $title, true);
        }
    }

    public function closetags($html) {
        return Mage::helper('gloss/post')->closetags($html);
    }

    protected function _prepareCollection($customFilters = array()) {

        if (!$this->getCachedCollection()) {

            $collection = Mage::getModel('gloss/gloss')->getCollection()
                    ->addPresentFilter()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->setOrder('created_time ', 'desc');

            if (!empty($customFilters)) {
                foreach ($customFilters as $filter => $value) {
                    $collection->{$filter}($value);
                }
            }

            $page = $this->getRequest()->getParam('page');
            Mage::getSingleton('gloss/status')->addEnabledFilterToCollection($collection);
            $collection->setPageSize((int) Mage::getStoreConfig(Den_Gloss_Helper_Config::XML_BLOG_PERPAGE));
            $collection->setCurPage($page);

            $this->setCachedCollection($collection);
        }

        return $this->getCachedCollection();
    }

   
}
