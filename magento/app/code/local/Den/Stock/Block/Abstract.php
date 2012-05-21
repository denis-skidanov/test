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
class Den_Stock_Block_Abstract extends Mage_Core_Block_Template {

    protected function _processCollection($collection, $category = false) {

        $route = Mage::helper('stock')->getRoute();

        foreach ($collection as $item) {
           
            /* Escape tags */
            Den_Stock_Helper_Data::escapeSpecialChars($item);
            
            
            if ($category) {                
                if (Mage::getStoreConfig('stock/stock/categories_urls')) {
                    $item->setAddress($this->getUrl($route . '/cat/' .  $this->getCat()->getIdentifier() . '/post/' . $item->getIdentifier()));
                } else {
                    $item->setAddress($this->getUrl($route . "/" . $item->getIdentifier()));
                }
            } else {
               $item->setAddress($this->getUrl($route . "/" . $item->getIdentifier()));
            }
 
            $item->setCreatedTime($this->formatTime($item->getCreatedTime(), Mage::getStoreConfig('stock/stock/dateformat'), true));
            $item->setUpdateTime($this->formatTime($item->getUpdateTime(), Mage::getStoreConfig('stock/stock/dateformat'), true));

            if (Mage::getStoreConfig(Den_Stock_Helper_Config::XML_BLOG_USESHORTCONTENT) && trim($item->getShortContent())) {
                $content = trim($item->getPostContent());
                $content = $this->closetags($content);
                //$content .= ' <a href="' . $this->getUrl($route . "/" . $item->getIdentifier()) . '" >' . $this->__('Read More') . '</a>';
                $item->setPostContent($content);
            } elseif ((int) Mage::getStoreConfig(Den_Stock_Helper_Config::XML_BLOG_READMORE) != 0) {
                
                
                $content = $item->getPostContent();
                $strManager = new Den_Stock_Helper_Substring(array('input' => Mage::helper('stock')->filterWYS($content)));
                $content = $strManager->getHtmlSubstr((int) Mage::getStoreConfig(Den_Stock_Helper_Config::XML_BLOG_READMORE));

                if ($strManager->getSymbolsCount() == Mage::getStoreConfig(Den_Stock_Helper_Config::XML_BLOG_READMORE)) {
                    $content .= ' <a href="' . $this->getUrl($route . "/" . $item->getIdentifier()) . '" >' . $this->__('Read More') . '</a>';
                }
                $item->setPostContent($content);
            }


            $comments = Mage::getModel('stock/comment')->getCollection()
                    ->addPostFilter($item->getPostId())
                    ->addApproveFilter(2);
            $item->setCommentCount(count($comments));

            $cats = Mage::getModel('stock/cat')->getCollection()
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
        if (Mage::getStoreConfig('stock/stock/bookmarkslist')) {
            $this->setTemplate('aw_stock/bookmark.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getTagsHtml($post) {

        if (trim($post->getTags())) {
            $this->setTemplate('aw_stock/line_tags.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getCommentsEnabled() {
        return Mage::getStoreConfig('stock/comments/enabled');
    }
 
    public function getPagesCollection($mode,$params = array()) {
        
          if ((int) Mage::getStoreConfig('stock/stock/perpage') != 0) { 
             
            if($mode == 'list') { $bool = false; }
            else { $bool = true; }

            $pager = Mage::getConfig()->getBlockClassName('stock/pager'); 
            $pager = new $pager();
            $pager->setTemplate('aw_stock/pager/list.phtml');
            $pager->setCategoryMode($bool); 
            
            
            foreach($params as $key => $param) {                
                $pager->{$key}($param);                
            }
           
            return $pager->renderView();
         }
     
    }
 

    public function addTopLink() {
        if (Mage::helper('stock')->getEnabled()) {
            $route = Mage::helper('stock')->getRoute();
            $title = Mage::getStoreConfig('stock/stock/title');
            $this->getParentBlock()->addLink($title, $route, $title, true, array(), 15, null, 'class="top-link-stock"');
        }
    }

    public function addFooterLink() {
        if (Mage::helper('stock')->getEnabled()) {
            $route = Mage::helper('stock')->getRoute();
            $title = Mage::getStoreConfig('stock/stock/title');
            $this->getParentBlock()->addLink($title, $route, $title, true);
        }
    }

    public function closetags($html) {
        return Mage::helper('stock/post')->closetags($html);
    }

    protected function _prepareCollection($customFilters = array()) {

        if (!$this->getCachedCollection()) {

            $collection = Mage::getModel('stock/stock')->getCollection()
                    ->addPresentFilter()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->setOrder('created_time ', 'desc');

            if (!empty($customFilters)) {
                foreach ($customFilters as $filter => $value) {
                    $collection->{$filter}($value);
                }
            }

            $page = $this->getRequest()->getParam('page');
            Mage::getSingleton('stock/status')->addEnabledFilterToCollection($collection);
            $collection->setPageSize((int) Mage::getStoreConfig(Den_Stock_Helper_Config::XML_BLOG_PERPAGE));
            $collection->setCurPage($page);

            $this->setCachedCollection($collection);
        }

        return $this->getCachedCollection();
    }

   
}
