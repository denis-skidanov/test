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
 * @package    Den_Reviews
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */
class Den_Reviews_Block_Reviews extends Den_Reviews_Block_Abstract {

    public function getPosts() {

        $collection = parent::_prepareCollection();

        if ($tag = $this->getRequest()->getParam('tag')) {
            $collection->addTagFilter(urldecode($tag));
        }

        parent::_processCollection($collection);

        return $collection;
    }

    public function getTagsHtml($post) {

        if (trim($post->getTags())) {
            $this->setTemplate('den_reviews/line_tags.phtml');
            $this->setPost($post);
            return $this->toHtml();
        }
        return;
    }

    public function getPages() {
           
         echo parent::getPagesCollection('list');
    }

    public function getRecent() {

        if (Mage::getStoreConfig(Den_Reviews_Helper_Config::XML_RECENT_SIZE) != 0) {
            $collection = Mage::getModel('reviews/reviews')->getCollection()
                    ->addPresentFilter()
                    ->addStoreFilter(Mage::app()->getStore()->getId())
                    ->setOrder('created_time ', 'desc');

            $route = Mage::helper('reviews')->getRoute();

            Mage::getSingleton('reviews/status')->addEnabledFilterToCollection($collection);
            $collection->setPageSize(Mage::getStoreConfig(Den_Reviews_Helper_Config::XML_RECENT_SIZE));
            $collection->setCurPage(1);
            foreach ($collection as $item) {
                $item->setAddress($this->getUrl($route . "/" . $item->getIdentifier()));
            }
            return $collection;
        } else {
            return false;
        }
    }

    public function getCategories() {

        $collection = Mage::getModel('reviews/cat')->getCollection()->addStoreFilter(Mage::app()->getStore()->getId())->setOrder('sort_order ', 'asc');
        $route = Mage::helper('reviews')->getRoute();

        foreach ($collection as $item) {
            $item->setAddress($this->getUrl($route . "/cat/" . $item->getIdentifier()));
        }
        return $collection;
    }

    protected function _prepareLayout() {

        $route = Mage::helper('reviews')->getRoute();
        $isReviewsPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'reviews';

        // show breadcrumbs
        if ($isReviewsPage && Mage::getStoreConfig('reviews/reviews/reviewscrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
            $breadcrumbs->addCrumb('home', array('label' => Mage::helper('reviews')->__('Home'), 'title' => Mage::helper('reviews')->__('Go to Home Page'), 'link' => Mage::getBaseUrl()));
            ;
            if ($tag = @urldecode($this->getRequest()->getParam('tag'))) {
                $breadcrumbs->addCrumb('reviews', array('label' => Mage::getStoreConfig('reviews/reviews/title'), 'title' => Mage::helper('reviews')->__('Return to ' . Mage::getStoreConfig('reviews/reviews/title')), 'link' => Mage::getUrl($route)));
                $breadcrumbs->addCrumb('reviews_tag', array('label' => Mage::helper('reviews')->__('Tagged with "%s"', Mage::helper('reviews')->convertSlashes($tag)), 'title' => Mage::helper('reviews')->__('Tagged with "%s"', $tag)));
            } else {
                $breadcrumbs->addCrumb('reviews', array('label' => Mage::getStoreConfig('reviews/reviews/title')));
            }
        }
    }

    public function _toHtml() {
        if (Mage::helper('reviews')->getEnabled()) {
            $isLeft = ($this->getParentBlock() === $this->getLayout()->getBlock('left'));
            $isRight = ($this->getParentBlock() === $this->getLayout()->getBlock('right'));

            $isReviewsPage = Mage::app()->getFrontController()->getAction()->getRequest()->getModuleName() == 'reviews';

            $leftAllowed = ($isReviewsPage && Mage::getStoreConfig('reviews/menu/left') == 2) || (Mage::getStoreConfig('reviews/menu/left') == 1);
            $rightAllowed = ($isReviewsPage && Mage::getStoreConfig('reviews/menu/right') == 2) || (Mage::getStoreConfig('reviews/menu/right') == 1);

            if (!$leftAllowed && $isLeft) {
                return '';
            }
            if (!$rightAllowed && $isRight) {
                return '';
            }
            try {
                if (Mage::getModel('widget/template_filter'))
                    $processor = Mage::getModel('widget/template_filter');
                return $processor->filter(parent::_toHtml());
            } catch (Exception $ex) {
                return parent::_toHtml();
            }
        }
    }

}
