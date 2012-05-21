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
 * @package    Den_Job
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Reviews_Block_Manage_Comment_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form(array(
                                      'id' => 'edit_form',
                                      'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                                      'method' => 'post',
                                   ));
	  $form->setUseContainer(true);
      $this->setForm($form);
	  
      $fieldset = $form->addFieldset('comment_form', array('legend'=>Mage::helper('reviews')->__('Comment Information')));
     
      $fieldset->addField('user', 'text', array(
          'label'     => Mage::helper('reviews')->__('User'),
          'name'      => 'user',
      ));
	  
	  $fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('reviews')->__('Email Address'),
          'name'      => 'email',
      ));
	
	  $fieldset->addField('tel', 'text', array(
          'label'     => Mage::helper('reviews')->__('Telefon'),
          'name'      => 'tel',
      ));
  $fieldset->addField('spot', 'text', array(
          'label'     => Mage::helper('reviews')->__('Spot'),
          'name'      => 'spot',
      ));
  $fieldset->addField('company', 'text', array(
          'label'     => Mage::helper('reviews')->__('Company'),
          'name'      => 'company',
      ));

      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('reviews')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('reviews')->__('Unapproved'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('reviews')->__('Approved'),
              ),
          ),
      ));
     
      $fieldset->addField('comment', 'editor', array(
          'name'      => 'comment',
          'label'     => Mage::helper('reviews')->__('Comment'),
          'title'     => Mage::helper('reviews')->__('Comment'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getReviewsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getReviewsData());
          Mage::getSingleton('adminhtml/session')->setReviewsData(null);
      } elseif ( Mage::registry('reviews_data') ) {
          $form->setValues(Mage::registry('reviews_data')->getData());
      }
      return parent::_prepareForm();
  }
}
