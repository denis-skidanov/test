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
class Den_Job_Block_Manage_Job_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('job_form', array('legend' => Mage::helper('job')->__('Post information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('job')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('identifier', 'text', array(
            'label' => Mage::helper('job')->__('Identifier'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'identifier',
            'class' => 'validate-identifier',
            'after_element_html' => '<span class="hint">(eg: domain.com/job/identifier)</span>',
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'stores[]',
                'label' => Mage::helper('cms')->__('Store View'),
                'title' => Mage::helper('cms')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }

        $categories = array();
        $collection = Mage::getModel('job/cat')->getCollection()->setOrder('sort_order', 'asc');
        foreach ($collection as $cat) {
            $categories[] = ( array(
                'label' => (string) $cat->getTitle(),
                'value' => $cat->getCatId()
                    ));
        }

        $fieldset->addField('cat_id', 'multiselect', array(
            'name' => 'cats[]',
            'label' => Mage::helper('job')->__('Category'),
            'title' => Mage::helper('job')->__('Category'),
            'required' => true,
            'style' => 'height:100px',
            'values' => $categories,
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('job')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('job')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('job')->__('Disabled'),
                ),
                array(
                    'value' => 3,
                    'label' => Mage::helper('job')->__('Hidden'),
                ),
            ),
            'after_element_html' => '<span class="hint">(Hidden Pages will not show in the job but can still be accessed directly)</span>',
        ));

        $fieldset->addField('comments', 'select', array(
            'label' => Mage::helper('job')->__('Enable Comments'),
            'name' => 'comments',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('job')->__('Enabled'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('job')->__('Disabled'),
                ),
            ),
            'after_element_html' => '<span class="hint">Disabling will close the post to new comments</span>',
        ));

        $fieldset->addField('tags', 'text', array(
            'name' => 'tags',
            'label' => Mage::helper('job')->__('Tags'),
            'title' => Mage::helper('job')->__('tags'),
            'style' => 'width:700px;',
            'after_element_html' => Mage::helper('job')->__('Use space or comma as separators'),
        ));

        try {
            $config = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
            $config->setData(Mage::helper('job')->recursiveReplace(
                            '/job_admin/',
                            '/' . (string) Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName') . '/',
                            $config->getData()
                    )
            );
        } catch (Exception $ex) {
            $config = null;
        }

        if (Mage::getStoreConfig('job/job/useshortcontent')) {
            $fieldset->addField('short_content', 'editor', array(
                'name' => 'short_content',
                'label' => Mage::helper('job')->__('Short Content'),
                'title' => Mage::helper('job')->__('Short Content'),
                'style' => 'width:700px; height:100px;',
                'config' => $config,
            ));
        }
        $fieldset->addField('post_content', 'editor', array(
            'name' => 'post_content',
            'label' => Mage::helper('job')->__('Content'),
            'title' => Mage::helper('job')->__('Content'),
            'style' => 'width:700px; height:500px;',
            'config' => $config
        ));

        if (Mage::getSingleton('adminhtml/session')->getBlogData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBlogData());
            Mage::getSingleton('adminhtml/session')->setBlogData(null);
        } elseif (Mage::registry('job_data')) {
            Mage::registry('job_data')->setTags(Mage::helper('job')->convertSlashes(Mage::registry('job_data')->getTags()));
            $form->setValues(Mage::registry('job_data')->getData());
        }
        return parent::_prepareForm();
    }

}
