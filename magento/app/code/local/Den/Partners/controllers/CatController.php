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

class Den_Partners_CatController extends Mage_Core_Controller_Front_Action {
	public function preDispatch() {
		parent::preDispatch();
        if(!Mage::helper('partners')->getEnabled())
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
	}

	public function viewAction() {

		$identifier = $this->getRequest()->getParam('identifier', $this->getRequest()->getParam('id', false));

		if (!Mage::helper('partners/cat')->renderPage($this, $identifier)) {
			$this->_forward('NoRoute');
		}

	}

	public function noRouteAction($coreRoute = null) {
		$this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
		$this->getResponse()->setHeader('Status','404 File not found');

		$pageId = Mage::getStoreConfig('web/default/cms_no_route');
		if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
			$this->_forward('defaultNoRoute');
		}
	}
}
