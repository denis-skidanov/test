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
 * @package    Den_Faq
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */
class Den_Faq_Block_Faq extends Den_Faq_Block_Abstract {

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
            $this->setTemplate('den_faq/line_tags.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getPages() {
           
         echo parent::getPagesCollection('list');
    }

    public function getRecent() {

        if (Mage::getStoreConfig(Den_Faq_Helper_Config::XML_RECENT_SIZE) != 0) {
            $collection = Mage::getModel('faq/faq')->getCollection()
                    ->addPresentFilter()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->setOrder('created_time ', 'desc');

            $route = Mage::helper('faq')->getRoute();

            Mage::getSingleton('faq/status')->addEnabledFilterToCollection($collection);
            $collection->setPageSize(Mage::getStoreConfig(Den_Faq_Helper_Config::XML_RECENT_SIZE));
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

        $collection = Mage::getModel('faq/cat')->getCollection()->addStoreFilter(Mage::app()->getStore()->getId())->setOrder('sort_order ', 'asc');
        $route = Mage::helper('faq')->getRoute();

        foreach ($collection as $item) {
            $item->setAddress($this->getUrl($route . "/cat/" . $item->getIdentifier()));
        }
        return $collection;
    }

    protected function _prepareLayout() {

        $route = Mage::helper('faq')->getRoute();
        $isBlogPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'faq';

        // show breadcrumbs
        if ($isBlogPage && Mage::getStoreConfig('faq/faq/faqcrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', array('label' => Mage::helper('faq')->__('Home'), 'title' => Mage::helper('faq')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
            ;
            if ($tag = @urldecode($this->getRequest()->getParam('tag'))) {
                $breadcrumbs->addCrumb('faq', array('label' => Mage::getStoreConfig('faq/faq/title'), 'title' => Mage::helper('faq')->__('Return to ' . Mage::getStoreConfig('faq/faq/title')), 'link' => Mage::getUrl($route)));
                $breadcrumbs->addCrumb('faq_tag', array('label' => Mage::helper('faq')->__('Tagged with "%s"', Mage::helper('faq')->convertSlashes($tag)), 'title' => Mage::helper('faq')->__('Tagged with "%s"', $tag)));
            } else {
                $breadcrumbs->addCrumb('faq', array('label' => Mage::getStoreConfig('faq/faq/title')));
            }
        }
    }

    public function _toHtml() {
        if (Mage::helper('faq')->getEnabled()) {
            $isLeft = ($this->getParentBlock() === $this->getLayout()->getBlock('left'));
            $isRight = ($this->getParentBlock() === $this->getLayout()->getBlock('right'));

            $isBlogPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'faq';

            $leftAllowed = ($isBlogPage && Mage::getStoreConfig('faq/menu/left') == 2) || (Mage::getStoreConfig('faq/menu/left') == 1);
            $rightAllowed = ($isBlogPage && Mage::getStoreConfig('faq/menu/right') == 2) || (Mage::getStoreConfig('faq/menu/right') == 1);

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
