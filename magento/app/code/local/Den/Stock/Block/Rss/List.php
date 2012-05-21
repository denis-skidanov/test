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

class Den_Stock_Block_Rss_List extends Mage_Rss_Block_List {
    public function getRssMiscFeeds() {
        parent::getRssMiscFeeds();
        $this->AWBlogFeed();
        return $this->getRssFeeds();
    }
    
    public function AWBlogFeed() {
        $route = Mage::helper('stock')->getRoute().'/rss';
        $title = Mage::getStoreConfig('stock/stock/title');
        $this->addRssFeed($route, $title);
        return $this;
    }
}
