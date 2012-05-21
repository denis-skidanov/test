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

class Den_Stock_Block_Manage_Comment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'stock';
        $this->_controller = 'manage_comment';
        
        $this->_updateButton('save', 'label', Mage::helper('stock')->__('Save Comment'));
        $this->_updateButton('delete', 'label', Mage::helper('stock')->__('Delete Comment'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('stock_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'stock_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'stock_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('stock_data') && Mage::registry('stock_data')->getId() ) {
            return Mage::helper('stock')->__("Edit Comment By '%s'", $this->htmlEscape(Mage::registry('stock_data')->getUser()));
        } else {
            return Mage::helper('stock')->__('Add Comment');
        }
    }
}
