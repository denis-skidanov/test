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
class Den_Gloss_Block_Pager extends Mage_Core_Block_Template {

    public function getCollection() {

        if ($this->getData('collection')) {
            return $this->getData('collection');
        }

        $collection = Mage::getModel('gloss/gloss')->getCollection()
                ->addPresentFilter()
                ->addStoreFilter()
                ->setOrder('created_time ', 'desc');

        $tagFilter = '';
        if ($tag = $this->getRequest()->getParam('tag')) {
            $collection->addTagFilter(urldecode($tag));
            $tagFilter = "/tag/{$tag}/";
        }

        $this->setTagFilter($tagFilter);

        Mage::getSingleton('gloss/status')->addEnabledFilterToCollection($collection);

        if ($this->getCategoryMode()) {

            Mage::getSingleton('gloss/status')->addCatFilterToCollection($collection, $this->getCatId());
        }

        $this->setData('collection', $collection);

        return $this->getData('collection');
    }

    public function getCurrentPage() {

        $currentPage = (int) $this->getRequest()->getParam('page');
        if (!$currentPage) {
            $currentPage = 1;
        }

        return $currentPage;
    }

    public function getPagesCount() {

        return ceil($this->getCollection()->count() / (int) Mage::getStoreConfig('gloss/gloss/perpage'));
    }

}
