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

class Den_Stock_Helper_Cat extends Mage_Core_Helper_Abstract
{
    /**
    * Renders CMS page
    *
    * Call from controller action
    *
    * @param Mage_Core_Controller_Front_Action $action
    * @param integer $pageId
    * @return boolean
    */
    public function renderPage(Mage_Core_Controller_Front_Action $action, $identifier=null)
    {
        if (!$cat_id = Mage::getSingleton('stock/cat')->load($identifier)->getcatId())
		{
			return false;
		}

		$page_title = Mage::getSingleton('stock/cat')->load($identifier)->getTitle();
		$stock_title = Mage::getStoreConfig('stock/stock/title') . " - ";
	
        $action->loadLayout();
		if ($storage = Mage::getSingleton('customer/session')) {
            $action->getLayout()->getMessagesBlock()->addMessages($storage->getMessages(true));
        }
		$action->getLayout()->getBlock('head')->setTitle($stock_title . $page_title);
        /*
        if (Mage::getStoreConfig('stock/rss/enable'))
		{
            Mage::helper('stock')->addRss($action->getLayout()->getBlock('head'), Mage::getUrl(Mage::getStoreConfig('stock/stock/route') . "/cat/" .$identifier) . "rss");
		}
        */
		$action->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('stock/stock/layout'));
        $action->renderLayout();

        return true;
    }
}
