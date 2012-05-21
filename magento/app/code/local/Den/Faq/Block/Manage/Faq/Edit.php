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

class Den_Faq_Block_Manage_Faq_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'faq';
        $this->_controller = 'manage_faq';
        
        $this->_updateButton('save', 'label', Mage::helper('faq')->__('Save Post'));
        $this->_updateButton('delete', 'label', Mage::helper('faq')->__('Delete Post'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
        
        if($this->getRequest()->getParam('id')) {
            $this->_addButton('diplicate', array(
                'label' => Mage::helper('faq')->__('Duplicate Post'),
                'onclick' => 'duplicate()',
                'class' => 'save'
            ), 0);
        }

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('faq_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'post_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'post_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
            
            function duplicate() {
                $(editForm.formId).action = '".$this->getUrl('*/*/duplicate')."';
                editForm.submit();
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('faq_data') && Mage::registry('faq_data')->getId() ) {
            return Mage::helper('faq')->__("Edit Post '%s'", $this->htmlEscape(Mage::registry('faq_data')->getTitle()));
        } else {
            return Mage::helper('faq')->__('Add Post');
        }
    }
}
