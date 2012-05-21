<?php
class Den_Staff_Block_Last extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface{

    protected function _toHtml()
    {
        $this->setTemplate('den_staff/widget_post.phtml');
        return parent::_toHtml();
    }

	public function getRecent()
   	{
		if ($this->getBlocksCount() != 0)
		{
			$collection = Mage::getModel('staff/staff')->getCollection()
			->addPresentFilter()
			->addStoreFilter(Mage::app()->getStore()->getId())
            ->addCatsFilter($this->getCategories())
			->setOrder('created_time ', 'desc')
            ;

			$route = Mage::helper('staff')->getRoute();

			Mage::getSingleton('staff/status')->addEnabledFilterToCollection($collection);
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