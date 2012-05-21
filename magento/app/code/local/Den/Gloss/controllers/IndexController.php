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

class Den_Gloss_IndexController extends Mage_Core_Controller_Front_Action {

    public function preDispatch(){
        parent::preDispatch();
        if(!Mage::helper('gloss')->getEnabled())
            $this->_redirectUrl(Mage::helper('core/url')->getHomeUrl());
    }
    public function indexAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('gloss/gloss/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('gloss/gloss/title'));
			$head->setKeywords(Mage::getStoreConfig('gloss/gloss/keywords'));
			$head->setDescription(Mage::getStoreConfig('gloss/gloss/description'));
            /*
			if (Mage::getStoreConfig('gloss/rss/enable')) {
				$route = Mage::helper('gloss')->getRoute();
				Mage::helper('gloss')->addRss($head, Mage::getUrl($route) . "rss");
			}
            */
		}
		$this->renderLayout();
	}

	public function listAction() {

		$this->loadLayout();

		$this->getLayout()->getBlock('root')->setTemplate(Mage::getStoreConfig('gloss/gloss/layout'));

		if ($head = $this->getLayout()->getBlock('head')) {
			$head->setTitle(Mage::getStoreConfig('gloss/gloss/title'));
			$head->setKeywords(Mage::getStoreConfig('gloss/gloss/keywords'));
			$head->setDescription(Mage::getStoreConfig('gloss/gloss/description'));
            /*
			if (Mage::getStoreConfig('gloss/rss/enable')) {
				$route = Mage::helper('gloss')->getRoute();
				if($tag = $this->getRequest()->getParam('tag')) {
					Mage::helper('gloss')->addRss($head, Mage::getUrl($route) . "tag/$tag/" . "rss");
				}else {
					Mage::helper('gloss')->addRss($head, Mage::getUrl($route) . "rss");
				}
			}
            */
		}
		$this->renderLayout();
	}
	
	public function testAction(){
		$a = $_POST['test'];
		$collection = Mage::getModel('gloss/gloss')->getCollection()
		->addFieldToFilter('title', array('like' => $a.'%'));
		$content = '';
		$i=0;
		foreach ($collection as $post){
			if($i>=1){
				$content = $content.'<div class="postWrapper" style="display:none"><div class="postTitle"><h2>'.$post->getTitle().'</h2></div><div class="postContent">'.$post->getPostContent().'</div></div>';
			}else{
				$content = $content.'<div class="postWrapper"><div class="postTitle"><h2>'.$post->getTitle().'</h2></div><div class="postContent">'.$post->getPostContent().'</div></div>';
			}
			$i++;
			
		}
		if($i>=1){
			echo $content=$content.'<input type="button" id="more" value="показать еще">';
		}else{
			echo $content;
		}
	}
}
