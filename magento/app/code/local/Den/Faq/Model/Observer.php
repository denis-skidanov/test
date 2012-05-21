<?php

/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-M1.txt
 *
 * @category   AW
 * @package    AW_Ascurl
 * @copyright  Copyright (c) 2008-2009 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-M1.txt
 */
class Den_Faq_Model_Observer {

    public function addBlogSection($observer) {
        $sitemapObject = $observer->getSitemapObject();
        if (!($sitemapObject instanceof Mage_Sitemap_Model_Sitemap))
            throw new Exception(Mage::helper('faq')->__('Error during generation sitemap'));

        $storeId = $sitemapObject->getStoreId();
        $date = Mage::getSingleton('core/date')->gmtDate('Y-m-d');
        $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
        /**
         * Generate faq pages sitemap
         */
        $changefreq = (string) Mage::getStoreConfig('sitemap/faq/changefreq');
        $priority = (string) Mage::getStoreConfig('sitemap/faq/priority');
        $collection = Mage::getModel('faq/faq')->getCollection()->addStoreFilter($storeId);
        Mage::getSingleton('faq/status')->addEnabledFilterToCollection($collection);
        $route = Mage::getStoreConfig('faq/faq/route');
        if ($route == "") {
            $route = "faq";
        }
        foreach ($collection as $item) {
            $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                            htmlspecialchars($baseUrl . $route . '/' . $item->getIdentifier()),
                            $date,
                            $changefreq,
                            $priority
            );

            $sitemapObject->sitemapFileAddLine($xml);
        }
        unset($collection);
    }

    public function rewriteRssList($observer) {
        if (Mage::helper('faq')->getEnabled()) {
            $node = Mage::getConfig()->getNode('global/blocks/rss/rewrite');
            foreach (Mage::getConfig()->getNode('global/blocks/rss/drewrite')->children() as $dnode)
                $node->appendChild($dnode);
        }
    }

    public function provideIE9Compatibility($observer) {

        $body = $observer->getResponse()->getBody();
        if (strpos(strToLower($body), 'x-ua-compatible') !== false) {
            return;
        }
        $body = preg_replace('{(</title>)}i', '$1' . '<meta http-equiv="X-UA-Compatible" content="IE=8" />', $body);
        $observer->getResponse()->setBody($body);
    }

}
