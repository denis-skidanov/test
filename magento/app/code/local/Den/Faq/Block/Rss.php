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
 * @package    Den_Faq
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Faq_Block_Rss extends Mage_Rss_Block_Abstract
{
    protected function _construct()
    {
        /*
        * setting cache to save the rss for 10 minutes
        */
	    $this->setCacheKey('rss_catalog_category_'
            .Mage::app()->getStore()->getId().'_'
            .$this->getRequest()->getParam('cid').'_'
            .$this->getRequest()->getParam('sid')
        );
        $this->setCacheLifetime(600);
    }

    protected function _toHtml()
    {
		$rssObj = Mage::getModel('rss/rss');

		$route = Mage::helper('faq')->getRoute();
		
		$url = $this->getUrl($route);
		$title = Mage::getStoreConfig('faq/faq/title');
		$data = array('title' => $title,
			'description' => $title,
			'link'        => $url,
			'charset'     => 'UTF-8'
			);
				
		if (Mage::getStoreConfig('faq/rss/image') != "")
		{
			$data['image'] = $this->getSkinUrl(Mage::getStoreConfig('faq/rss/image'));
		}
				
		$rssObj->_addHeader($data);
					
		$collection = Mage::getModel('faq/faq')->getCollection()
		->addStoreFilter(Mage::app()->getStore()->getId())
		->setOrder('created_time ', 'desc');
		
		$identifier = $this->getRequest()->getParam('identifier');
		
		$tag = $this->getRequest()->getParam('tag');
		if($tag){
			$collection->addTagFilter(urldecode($tag));
		}
		
		
		if ($cat_id = Mage::getSingleton('faq/cat')->load($identifier)->getcatId()){
			Mage::getSingleton('faq/status')->addCatFilterToCollection($collection, $cat_id);
		}
		
		
		Mage::getSingleton('faq/status')->addEnabledFilterToCollection($collection);
		
		$collection->setPageSize((int)Mage::getStoreConfig('faq/rss/posts'));
		$collection->setCurPage(1);



		if ($collection->getSize()>0) {
			foreach ($collection as $post) {
			
				$data = array(
							'title'         => $post->getTitle(),
							'link'          => $this->getUrl($route . "/" . $post->getIdentifier()),
							'description'   => $post->getPostContent(),
							'lastUpdate' 	=> strtotime($post->getCreatedTime()),
							);
							
				$rssObj->_addEntry($data);
			}
		}

		return $rssObj->createRssXml();
    }
}
