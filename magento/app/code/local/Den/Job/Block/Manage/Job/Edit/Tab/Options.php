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
class Den_Job_Block_Manage_Job_Edit_Tab_Options extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('job_form', array('legend' => Mage::helper('job')->__('Meta Data')));

        $fieldset->addField('meta_keywords', 'editor', array(
            'name' => 'meta_keywords',
            'label' => Mage::helper('job')->__('Keywords'),
            'title' => Mage::helper('job')->__('Meta Keywords'),
            'style' => 'width: 520px;',
        ));

        $fieldset->addField('meta_description', 'editor', array(
            'name' => 'meta_description',
            'label' => Mage::helper('job')->__('Description'),
            'title' => Mage::helper('job')->__('Meta Description'),
            'style' => 'width: 520px;',
        ));

        $fieldset = $form->addFieldset('job_options', array('legend' => Mage::helper('job')->__('Advanced Post Options')));

        $fieldset->addField('user', 'text', array(
            'label' => Mage::helper('job')->__('Poster'),
            'name' => 'user',
            'style' => 'width: 520px;',
            'after_element_html' => '<span class="hint">(Leave blank to use current user name)</span>',
        ));


        $outputFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);

        $fieldset->addField('created_time', 'date', array(
            'name' => 'created_time',
            'label' => $this->__('Created on'),
            'title' => $this->__('Created on'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => $outputFormat,
            'time' => true,
        ));



        if (Mage::getSingleton('adminhtml/session')->getBlogData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBlogData());
            Mage::getSingleton('adminhtml/session')->setBlogData(null);
        } elseif ($data = Mage::registry('job_data')) {

            $form->setValues(Mage::registry('job_data')->getData());

            if ($data->getData('created_time')) {
                $form->getElement('created_time')->setValue(
                        Mage::app()->getLocale()->date($data->getData('created_time'), Varien_Date::DATETIME_INTERNAL_FORMAT)
                );
            }
        }


        return parent::_prepareForm();
    }

}
