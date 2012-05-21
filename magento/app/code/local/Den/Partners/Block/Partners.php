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
 * @package    Den_Partners
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */
class Den_Partners_Block_Partners extends Den_Partners_Block_Abstract {

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
            $this->setTemplate('den_partners/line_tags.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getPages() {
           
         echo parent::getPagesCollection('list');
    }

    public function getRecent() {

        if (Mage::getStoreConfig(Den_Partners_Helper_Config::XML_RECENT_SIZE) != 0) {
            $collection = Mage::getModel('partners/partners')->getCollection()
                    ->addPresentFilter()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->setOrder('created_time ', 'desc');

            $route = Mage::helper('partners')->getRoute();

            Mage::getSingleton('partners/status')->addEnabledFilterToCollection($collection);
            $collection->setPageSize(Mage::getStoreConfig(Den_Partners_Helper_Config::XML_RECENT_SIZE));
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

        $collection = Mage::getModel('partners/cat')->getCollection()->addStoreFilter(Mage::app()->getStore()->getId())->setOrder('sort_order ', 'asc');
        $route = Mage::helper('partners')->getRoute();

        foreach ($collection as $item) {
            $item->setAddress($this->getUrl($route . "/cat/" . $item->getIdentifier()));
        }
        return $collection;
    }

    protected function _prepareLayout() {

        $route = Mage::helper('partners')->getRoute();
        $isPartnersPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'partners';

        // show breadcrumbs
        if ($isPartnersPage && Mage::getStoreConfig('partners/partners/partnerscrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', array('label' => Mage::helper('partners')->__('Home'), 'title' => Mage::helper('partners')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
            ;
            if ($tag = @urldecode($this->getRequest()->getParam('tag'))) {
                $breadcrumbs->addCrumb('partners', array('label' => Mage::getStoreConfig('partners/partners/title'), 'title' => Mage::helper('partners')->__('Return to ' . Mage::getStoreConfig('partners/partners/title')), 'link' => Mage::getUrl($route)));
                $breadcrumbs->addCrumb('partners_tag', array('label' => Mage::helper('partners')->__('Tagged with "%s"', Mage::helper('partners')->convertSlashes($tag)), 'title' => Mage::helper('partners')->__('Tagged with "%s"', $tag)));
            } else {
                $breadcrumbs->addCrumb('partners', array('label' => Mage::getStoreConfig('partners/partners/title')));
            }
        }
    }

    public function _toHtml() {
        if (Mage::helper('partners')->getEnabled()) {
            $isLeft = ($this->getParentBlock() === $this->getLayout()->getBlock('left'));
            $isRight = ($this->getParentBlock() === $this->getLayout()->getBlock('right'));

            $isPartnersPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'partners';

            $leftAllowed = ($isPartnersPage && Mage::getStoreConfig('partners/menu/left') == 2) || (Mage::getStoreConfig('partners/menu/left') == 1);
            $rightAllowed = ($isPartnersPage && Mage::getStoreConfig('partners/menu/right') == 2) || (Mage::getStoreConfig('partners/menu/right') == 1);

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
