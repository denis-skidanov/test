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
class Den_Stock_Block_Stock extends Den_Stock_Block_Abstract {

    public function getPosts() {

        $collection = parent::_prepareCollection();

        if ($tag = $this->getRequest()->getParam('tag')) {
            $collection->addTagFilter(urldecode($tag));
        }

        parent::_processCollection($collection);

        return $collection;
    }

    public function getTagsHtml($post) {

        if (trim($post->getTags())) {
            $this->setTemplate('den_stock/line_tags.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getPages() {
           
         echo parent::getPagesCollection('list');
    }

    public function getRecent() {

        if (Mage::getStoreConfig(Den_Stock_Helper_Config::XML_RECENT_SIZE) != 0) {
            $collection = Mage::getModel('stock/stock')->getCollection()
                    ->addPresentFilter()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->setOrder('created_time ', 'desc');

            $route = Mage::helper('stock')->getRoute();

            Mage::getSingleton('stock/status')->addEnabledFilterToCollection($collection);
            $collection->setPageSize(Mage::getStoreConfig(Den_Stock_Helper_Config::XML_RECENT_SIZE));
            $collection->setCurPage(1);
            foreach ($collection as $item) {
                $item->setAddress($this->getUrl($route . "/" . $item->getIdentifier()));
            }
            return $collection;
        } else {
            return false;
        }
    }

    public function getCategories() {

        $collection = Mage::getModel('stock/cat')->getCollection()->addStoreFilter(Mage::app()->getStore()->getId())->setOrder('sort_order ', 'asc');
        $route = Mage::helper('stock')->getRoute();

        foreach ($collection as $item) {
            $item->setAddress($this->getUrl($route . "/cat/" . $item->getIdentifier()));
        }
        return $collection;
    }

    protected function _prepareLayout() {

        $route = Mage::helper('stock')->getRoute();
        $isBlogPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'stock';

        // show breadcrumbs
        if ($isBlogPage && Mage::getStoreConfig('stock/stock/stockcrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', array('label' => Mage::helper('stock')->__('Home'), 'title' => Mage::helper('stock')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
            ;
            if ($tag = @urldecode($this->getRequest()->getParam('tag'))) {
                $breadcrumbs->addCrumb('stock', array('label' => Mage::getStoreConfig('stock/stock/title'), 'title' => Mage::helper('stock')->__('Return to ' . Mage::getStoreConfig('stock/stock/title')), 'link' => Mage::getUrl($route)));
                $breadcrumbs->addCrumb('stock_tag', array('label' => Mage::helper('stock')->__('Tagged with "%s"', Mage::helper('stock')->convertSlashes($tag)), 'title' => Mage::helper('stock')->__('Tagged with "%s"', $tag)));
            } else {
                $breadcrumbs->addCrumb('stock', array('label' => Mage::getStoreConfig('stock/stock/title')));
            }
        }
    }

    public function _toHtml() {
        if (Mage::helper('stock')->getEnabled()) {
            $isLeft = ($this->getParentBlock() === $this->getLayout()->getBlock('left'));
            $isRight = ($this->getParentBlock() === $this->getLayout()->getBlock('right'));

            $isBlogPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'stock';

            $leftAllowed = ($isBlogPage && Mage::getStoreConfig('stock/menu/left') == 2) || (Mage::getStoreConfig('stock/menu/left') == 1);
            $rightAllowed = ($isBlogPage && Mage::getStoreConfig('stock/menu/right') == 2) || (Mage::getStoreConfig('stock/menu/right') == 1);

            if (!$leftAllowed && $isLeft) {
                return '';
            }
            if (!$rightAllowed && $isRight) {
                return '';
            }
            try {
                if (Mage::getModel('widget/template_filter'))
                    $processor = Mage::getModel('widget/template_filter');
                return $processor->filter(parent::_toHtml());
            } catch (Exception $ex) {
                return parent::_toHtml();
            }
        }
    }

}
