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
class Den_Staff_Block_Staff extends Den_Staff_Block_Abstract {

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
            $this->setTemplate('den_staff/line_tags.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getPages() {
           
         echo parent::getPagesCollection('list');
    }

    public function getRecent() {

        if (Mage::getStoreConfig(Den_Staff_Helper_Config::XML_RECENT_SIZE) != 0) {
            $collection = Mage::getModel('staff/staff')->getCollection()
                    ->addPresentFilter()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->setOrder('created_time ', 'desc');

            $route = Mage::helper('staff')->getRoute();

            Mage::getSingleton('staff/status')->addEnabledFilterToCollection($collection);
            $collection->setPageSize(Mage::getStoreConfig(Den_Staff_Helper_Config::XML_RECENT_SIZE));
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

        $collection = Mage::getModel('staff/cat')->getCollection()->addStoreFilter(Mage::app()->getStore()->getId())->setOrder('sort_order ', 'asc');
        $route = Mage::helper('staff')->getRoute();

        foreach ($collection as $item) {
            $item->setAddress($this->getUrl($route . "/cat/" . $item->getIdentifier()));
        }
        return $collection;
    }

    protected function _prepareLayout() {

        $route = Mage::helper('staff')->getRoute();
        $isBlogPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'staff';

        // show breadcrumbs
        if ($isBlogPage && Mage::getStoreConfig('staff/staff/staffcrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', array('label' => Mage::helper('staff')->__('Home'), 'title' => Mage::helper('staff')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
            ;
            if ($tag = @urldecode($this->getRequest()->getParam('tag'))) {
                $breadcrumbs->addCrumb('staff', array('label' => Mage::getStoreConfig('staff/staff/title'), 'title' => Mage::helper('staff')->__('Return to ' . Mage::getStoreConfig('staff/staff/title')), 'link' => Mage::getUrl($route)));
                $breadcrumbs->addCrumb('staff_tag', array('label' => Mage::helper('staff')->__('Tagged with "%s"', Mage::helper('staff')->convertSlashes($tag)), 'title' => Mage::helper('staff')->__('Tagged with "%s"', $tag)));
            } else {
                $breadcrumbs->addCrumb('staff', array('label' => Mage::getStoreConfig('staff/staff/title')));
            }
        }
    }

    public function _toHtml() {
        if (Mage::helper('staff')->getEnabled()) {
            $isLeft = ($this->getParentBlock() === $this->getLayout()->getBlock('left'));
            $isRight = ($this->getParentBlock() === $this->getLayout()->getBlock('right'));

            $isBlogPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'staff';

            $leftAllowed = ($isBlogPage && Mage::getStoreConfig('staff/menu/left') == 2) || (Mage::getStoreConfig('staff/menu/left') == 1);
            $rightAllowed = ($isBlogPage && Mage::getStoreConfig('staff/menu/right') == 2) || (Mage::getStoreConfig('staff/menu/right') == 1);

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
