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
 * @package    Den_Reviews
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Reviews_IndexController extends Mage_Core_Controller_Front_Action {

    public function preDispatch(){
        parent::preDispatch();
        if(!Mage::helper('reviews')->getEnabled())
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
    }
    public function indexAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('reviews/reviews/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('reviews/reviews/title'));
			$head->setKeywords(Mage::getStoreConfig('reviews/reviews/keywords'));
			$head->setDescription(Mage::getStoreConfig('reviews/reviews/description'));
            /*
			if (Mage::getStoreConfig('reviews/rss/enable')) {
				$route = Mage::helper('reviews')->getRoute();
				Mage::helper('reviews')->addRss($head, Mage::getUrl($route) . "rss");
			}
            */
		}
		$this->renderLayout();
	}

	public function listAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('reviews/reviews/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('reviews/reviews/title'));
			$head->setKeywords(Mage::getStoreConfig('reviews/reviews/keywords'));
			$head->setDescription(Mage::getStoreConfig('reviews/reviews/description'));
            /*
			if (Mage::getStoreConfig('reviews/rss/enable')) {
				$route = Mage::helper('reviews')->getRoute();
				if($tag = $this->getRequest()->getParam('tag')) {
					Mage::helper('reviews')->addRss($head, Mage::getUrl($route) . "tag/$tag/" . "rss");
				}else {
					Mage::helper('reviews')->addRss($head, Mage::getUrl($route) . "rss");
				}
			}
            */
		}
		$this->renderLayout();
	}
}
