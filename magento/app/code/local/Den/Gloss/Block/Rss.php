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
 * @package    Den_Gloss
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Gloss_Block_Rss extends Mage_Rss_Block_Abstract
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

		$route = Mage::helper('gloss')->getRoute();
		
		$url = $this->getUrl($route);
		$title = Mage::getStoreConfig('gloss/gloss/title');
		$data = array('title' => $title,
			'description' => $title,
			'link'        => $url,
			'charset'     => 'UTF-8'
			);
				
		if (Mage::getStoreConfig('gloss/rss/image') != "")
		{
			$data['image'] = $this->getSkinUrl(Mage::getStoreConfig('gloss/rss/image'));
		}
				
		$rssObj->_addHeader($data);
					
		$collection = Mage::getModel('gloss/gloss')->getCollection()
		->addStoreFilter(Mage::app()->getStore()->getId())
		->setOrder('created_time ', 'desc');
		
		$identifier = $this->getRequest()->getParam('identifier');
		
		$tag = $this->getRequest()->getParam('tag');
		if($tag){
			$collection->addTagFilter(urldecode($tag));
		}
		
		
		if ($cat_id = Mage::getSingleton('gloss/cat')->load($identifier)->getcatId()){
			Mage::getSingleton('gloss/status')->addCatFilterToCollection($collection, $cat_id);
		}
		
		
		Mage::getSingleton('gloss/status')->addEnabledFilterToCollection($collection);
		
		$collection->setPageSize((int)Mage::getStoreConfig('gloss/rss/posts'));
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
