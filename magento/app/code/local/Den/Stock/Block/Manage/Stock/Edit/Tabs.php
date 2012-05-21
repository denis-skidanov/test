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

class Den_Stock_Block_Manage_Stock_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('stock_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('stock')->__('Post Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('stock')->__('Post Information'),
          'title'     => Mage::helper('stock')->__('Post Information'),
          'content'   => $this->getLayout()->createBlock('stock/manage_stock_edit_tab_form')->toHtml(),
      ));
	  
	  $this->addTab('options_section', array(
          'label'     => Mage::helper('stock')->__('Advanced Options'),
          'title'     => Mage::helper('stock')->__('Advanced Options'),
          'content'   => $this->getLayout()->createBlock('stock/manage_stock_edit_tab_options')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
