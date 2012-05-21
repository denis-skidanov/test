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

class Den_Partners_IndexController extends Mage_Core_Controller_Front_Action {

    public function preDispatch(){
        parent::preDispatch();
        if(!Mage::helper('partners')->getEnabled())
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
    }
    public function indexAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('partners/partners/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('partners/partners/title'));
			$head->setKeywords(Mage::getStoreConfig('partners/partners/keywords'));
			$head->setDescription(Mage::getStoreConfig('partners/partners/description'));
            /*
			if (Mage::getStoreConfig('partners/rss/enable')) {
				$route = Mage::helper('partners')->getRoute();
				Mage::helper('partners')->addRss($head, Mage::getUrl($route) . "rss");
			}
            */
		}
		$this->renderLayout();
	}

	public function listAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('partners/partners/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('partners/partners/title'));
			$head->setKeywords(Mage::getStoreConfig('partners/partners/keywords'));
			$head->setDescription(Mage::getStoreConfig('partners/partners/description'));
            /*
			if (Mage::getStoreConfig('partners/rss/enable')) {
				$route = Mage::helper('partners')->getRoute();
				if($tag = $this->getRequest()->getParam('tag')) {
					Mage::helper('partners')->addRss($head, Mage::getUrl($route) . "tag/$tag/" . "rss");
				}else {
					Mage::helper('partners')->addRss($head, Mage::getUrl($route) . "rss");
				}
			}
            */
		}
		$this->renderLayout();
	}
}
