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

class Den_Gloss_Block_Manage_Comment extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'manage_comment';
    $this->_blockGroup = 'gloss';
    $this->_headerText = Mage::helper('gloss')->__('Blog Comment Manager');
    parent::__construct();
	$this->setTemplate('den_gloss/comments.phtml');
	
  }
  
   protected function _prepareLayout() {
        $this->_removeButton('add');
        return parent::_prepareLayout();
    }
  
  
}
