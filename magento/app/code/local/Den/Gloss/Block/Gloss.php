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
class Den_Gloss_Block_Gloss extends Den_Gloss_Block_Abstract {

	public function getTest(){
		$collection = Mage::getModel('gloss/gloss')->getCollection();
		return $collection;
	}
	
	
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
            $this->setTemplate('den_gloss/line_tags.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getPages() {
           
         echo parent::getPagesCollection('list');
    }

    public function getRecent() {

        if (Mage::getStoreConfig(Den_Gloss_Helper_Config::XML_RECENT_SIZE) != 0) {
            $collection = Mage::getModel('gloss/gloss')->getCollection()
                    ->addPresentFilter()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->setOrder('created_time ', 'desc');

            $route = Mage::helper('gloss')->getRoute();

            Mage::getSingleton('gloss/status')->addEnabledFilterToCollection($collection);
            $collection->setPageSize(Mage::getStoreConfig(Den_Gloss_Helper_Config::XML_RECENT_SIZE));
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

        $collection = Mage::getModel('gloss/cat')->getCollection()->addStoreFilter(Mage::app()->getStore()->getId())->setOrder('sort_order ', 'asc');
        $route = Mage::helper('gloss')->getRoute();

        foreach ($collection as $item) {
            $item->setAddress($this->getUrl($route . "/cat/" . $item->getIdentifier()));
        }
        return $collection;
    }

    protected function _prepareLayout() {

        $route = Mage::helper('gloss')->getRoute();
        $isBlogPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'gloss';

        // show breadcrumbs
        if ($isBlogPage && Mage::getStoreConfig('gloss/gloss/glosscrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', array('label' => Mage::helper('gloss')->__('Home'), 'title' => Mage::helper('gloss')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
            ;
            if ($tag = @urldecode($this->getRequest()->getParam('tag'))) {
                $breadcrumbs->addCrumb('gloss', array('label' => Mage::getStoreConfig('gloss/gloss/title'), 'title' => Mage::helper('gloss')->__('Return to ' . Mage::getStoreConfig('gloss/gloss/title')), 'link' => Mage::getUrl($route)));
                $breadcrumbs->addCrumb('gloss_tag', array('label' => Mage::helper('gloss')->__('Tagged with "%s"', Mage::helper('gloss')->convertSlashes($tag)), 'title' => Mage::helper('gloss')->__('Tagged with "%s"', $tag)));
            } else {
                $breadcrumbs->addCrumb('gloss', array('label' => Mage::getStoreConfig('gloss/gloss/title')));
            }
        }
    }

    public function _toHtml() {
        if (Mage::helper('gloss')->getEnabled()) {
            $isLeft = ($this->getParentBlock() === $this->getLayout()->getBlock('left'));
            $isRight = ($this->getParentBlock() === $this->getLayout()->getBlock('right'));

            $isBlogPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'gloss';

            $leftAllowed = ($isBlogPage && Mage::getStoreConfig('gloss/menu/left') == 2) || (Mage::getStoreConfig('gloss/menu/left') == 1);
            $rightAllowed = ($isBlogPage && Mage::getStoreConfig('gloss/menu/right') == 2) || (Mage::getStoreConfig('gloss/menu/right') == 1);

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
