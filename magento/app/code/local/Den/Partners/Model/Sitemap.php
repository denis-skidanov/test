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

//require_once 'Mage/Sitemap/Model/Sitemap.php';

class Den_Partners_Model_Sitemap extends Mage_Sitemap_Model_Sitemap
{
    protected $io;

    public function generateXml()
    {
        if (Mage::helper('partners')->extensionEnabled('AW_Ascurl'))
        {
            return Mage::getModel('ascurl/sitemap')->setData($this->getData())->generateXml();
        }

        $this->fileCreate();

        $storeId = $this->getStoreId();
        $date    = Mage::getSingleton('core/date')->gmtDate('Y-m-d');
        $baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);

        /**
         * Generate categories sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/category/changefreq');
        $priority   = (string)Mage::getStoreConfig('sitemap/category/priority');
        $collection = Mage::getResourceModel('sitemap/catalog_category')->getCollection($storeId);
        foreach ($collection as $item) {
            $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($baseUrl . $item->getUrl()),
                $date,
                $changefreq,
                $priority
            );
            $this->sitemapFileAddLine($xml);
        }
        unset($collection);

        /**
         * Generate products sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/product/changefreq');
        $priority   = (string)Mage::getStoreConfig('sitemap/product/priority');
        $collection = Mage::getResourceModel('sitemap/catalog_product')->getCollection($storeId);
        foreach ($collection as $item) {
            $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($baseUrl . $item->getUrl()),
                $date,
                $changefreq,
                $priority
            );
            $this->sitemapFileAddLine($xml);
        }
        unset($collection);

        /**
         * Generate cms pages sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/page/changefreq');
        $priority   = (string)Mage::getStoreConfig('sitemap/page/priority');
        $collection = Mage::getResourceModel('sitemap/cms_page')->getCollection($storeId);
        foreach ($collection as $item) {
            $xml = sprintf('<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($baseUrl . $item->getUrl()),
                $date,
                $changefreq,
                $priority
            );
            $this->sitemapFileAddLine($xml);
        }
        unset($collection);

        Mage::dispatchEvent('sitemap_add_xml_block_to_the_end', array('sitemap_object' => $this));

        $this->fileClose();

        $this->setSitemapTime(Mage::getSingleton('core/date')->gmtDate('Y-m-d H:i:s'));
        $this->save();

        return $this;
    }
    
    protected function fileCreate()
    {
        $io = new Varien_Io_File();
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $this->getPath()));
        $io->streamOpen($this->getSitemapFilename());

        $io->streamWrite('<?xml version="1.0" encoding="UTF-8"?>' . "\n");
        $io->streamWrite('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
        $this->io = $io;
    }

    protected function fileClose()
    {
        $this->io->streamWrite('</urlset>');
        $this->io->streamClose();
    }

    public function sitemapFileAddLine($xml)
    {
        $this->io->streamWrite($xml);
    }
}
