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

class Den_Stock_IndexController extends Mage_Core_Controller_Front_Action {

    public function preDispatch(){
        parent::preDispatch();
        if(!Mage::helper('stock')->getEnabled())
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
    }
    public function indexAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('stock/stock/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('stock/stock/title'));
			$head->setKeywords(Mage::getStoreConfig('stock/stock/keywords'));
			$head->setDescription(Mage::getStoreConfig('stock/stock/description'));
            /*
			if (Mage::getStoreConfig('stock/rss/enable')) {
				$route = Mage::helper('stock')->getRoute();
				Mage::helper('stock')->addRss($head, Mage::getUrl($route) . "rss");
			}
            */
		}
		$this->renderLayout();
	}

	public function listAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('stock/stock/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('stock/stock/title'));
			$head->setKeywords(Mage::getStoreConfig('stock/stock/keywords'));
			$head->setDescription(Mage::getStoreConfig('stock/stock/description'));
            /*
			if (Mage::getStoreConfig('stock/rss/enable')) {
				$route = Mage::helper('stock')->getRoute();
				if($tag = $this->getRequest()->getParam('tag')) {
					Mage::helper('stock')->addRss($head, Mage::getUrl($route) . "tag/$tag/" . "rss");
				}else {
					Mage::helper('stock')->addRss($head, Mage::getUrl($route) . "rss");
				}
			}
            */
		}
		$this->renderLayout();
	}
}
