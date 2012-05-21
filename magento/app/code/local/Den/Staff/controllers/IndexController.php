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

class Den_Staff_IndexController extends Mage_Core_Controller_Front_Action {

    public function preDispatch(){
        parent::preDispatch();
        if(!Mage::helper('staff')->getEnabled())
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
    }
    public function indexAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('staff/staff/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('staff/staff/title'));
			$head->setKeywords(Mage::getStoreConfig('staff/staff/keywords'));
			$head->setDescription(Mage::getStoreConfig('staff/staff/description'));
            /*
			if (Mage::getStoreConfig('staff/rss/enable')) {
				$route = Mage::helper('staff')->getRoute();
				Mage::helper('staff')->addRss($head, Mage::getUrl($route) . "rss");
			}
            */
		}
		$this->renderLayout();
	}

	public function listAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('staff/staff/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('staff/staff/title'));
			$head->setKeywords(Mage::getStoreConfig('staff/staff/keywords'));
			$head->setDescription(Mage::getStoreConfig('staff/staff/description'));
            /*
			if (Mage::getStoreConfig('staff/rss/enable')) {
				$route = Mage::helper('staff')->getRoute();
				if($tag = $this->getRequest()->getParam('tag')) {
					Mage::helper('staff')->addRss($head, Mage::getUrl($route) . "tag/$tag/" . "rss");
				}else {
					Mage::helper('staff')->addRss($head, Mage::getUrl($route) . "rss");
				}
			}
            */
		}
		$this->renderLayout();
	}
}
