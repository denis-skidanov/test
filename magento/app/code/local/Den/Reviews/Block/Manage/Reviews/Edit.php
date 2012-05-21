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

class Den_Reviews_Block_Manage_Reviews_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'reviews';
        $this->_controller = 'manage_reviews';
        
        $this->_updateButton('save', 'label', Mage::helper('reviews')->__('Save Post'));
        $this->_updateButton('delete', 'label', Mage::helper('reviews')->__('Delete Post'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
        
        if($this->getRequest()->getParam('id')) {
            $this->_addButton('diplicate', array(
                'label' => Mage::helper('reviews')->__('Duplicate Post'),
                'onclick' => 'duplicate()',
                'class' => 'save'
            ), 0);
        }

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('reviews_content') == null) {
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
        if( Mage::registry('reviews_data') && Mage::registry('reviews_data')->getId() ) {
            return Mage::helper('reviews')->__("Edit Post '%s'", $this->htmlEscape(Mage::registry('reviews_data')->getTitle()));
        } else {
            return Mage::helper('reviews')->__('Add Post');
        }
    }
}
