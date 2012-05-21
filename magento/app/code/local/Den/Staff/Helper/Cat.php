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

class Den_Staff_Helper_Cat extends Mage_Core_Helper_Abstract
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
        if (!$cat_id = Mage::getSingleton('staff/cat')->load($identifier)->getcatId())
		{
			return false;
		}

		$page_title = Mage::getSingleton('staff/cat')->load($identifier)->getTitle();
		$staff_title = Mage::getStoreConfig('staff/staff/title') . " - ";
	
        $action->loadLayout();
		if ($storage = Mage::getSingleton('customer/session')) {
            $action->getLayout()->getMessagesBlock()->addMessages($storage->getMessages(true));
        }
		$action->getLayout()->getBlock('head')->setTitle($staff_title . $page_title);
        /*
        if (Mage::getStoreConfig('staff/rss/enable'))
		{
            Mage::helper('staff')->addRss($action->getLayout()->getBlock('head'), Mage::getUrl(Mage::getStoreConfig('staff/staff/route') . "/cat/" .$identifier) . "rss");
		}
        */
		$action->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('staff/staff/layout'));
        $action->renderLayout();

        return true;
    }
}
