<?php
class Den_Stock_Block_Last extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface{

    protected function _toHtml()
    {
        $this->setTemplate('den_stock/widget_post.phtml');
        return parent::_toHtml();
    }

	public function getRecent()
   	{
		if ($this->getBlocksCount() != 0)
		{
			$collection = Mage::getModel('stock/stock')->getCollection()
			->addPresentFilter()
			->addStoreFilter(Mage::app()->getStore()->getId())
            ->addCatsFilter($this->getCategories())
			->setOrder('created_time ', 'desc')
            ;

			$route = Mage::helper('stock')->getRoute();

			Mage::getSingleton('stock/status')->addEnabledFilterToCollection($collection);
			$collection->setPageSize($this->getBlocksCount());
			$collection->setCurPage(1);
			foreach ($collection as $item)
			{
				$item->setAddress($this->getUrl($route . "/" . $item->getIdentifier()));
			}
			return $collection;
		}
		else
		{
			return false;
		}
    }
}