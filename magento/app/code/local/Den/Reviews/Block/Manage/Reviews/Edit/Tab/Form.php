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
class Den_Reviews_Block_Manage_Reviews_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('reviews_form', array('legend' => Mage::helper('reviews')->__('Post information')));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('reviews')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('identifier', 'text', array(
            'label' => Mage::helper('reviews')->__('Identifier'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'identifier',
            'class' => 'validate-identifier',
            'after_element_html' => '<span class="hint">(eg: domain.com/reviews/identifier)</span>',
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
        $collection = Mage::getModel('reviews/cat')->getCollection()->setOrder('sort_order', 'asc');
        foreach ($collection as $cat) {
            $categories[] = ( array(
                'label' => (string) $cat->getTitle(),
                'value' => $cat->getCatId()
                    ));
        }

        $fieldset->addField('cat_id', 'multiselect', array(
            'name' => 'cats[]',
            'label' => Mage::helper('reviews')->__('Category'),
            'title' => Mage::helper('reviews')->__('Category'),
            'required' => true,
            'style' => 'height:100px',
            'values' => $categories,
        ));

        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('reviews')->__('Status'),
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('reviews')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('reviews')->__('Disabled'),
                ),
                array(
                    'value' => 3,
                    'label' => Mage::helper('reviews')->__('Hidden'),
                ),
            ),
            'after_element_html' => '<span class="hint">(Hidden Pages will not show in the reviews but can still be accessed directly)</span>',
        ));

        $fieldset->addField('comments', 'select', array(
            'label' => Mage::helper('reviews')->__('Enable Comments'),
            'name' => 'comments',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => Mage::helper('reviews')->__('Enabled'),
                ),
                array(
                    'value' => 1,
                    'label' => Mage::helper('reviews')->__('Disabled'),
                ),
            ),
            'after_element_html' => '<span class="hint">Disabling will close the post to new comments</span>',
        ));

        $fieldset->addField('tags', 'text', array(
            'name' => 'tags',
            'label' => Mage::helper('reviews')->__('Tags'),
            'title' => Mage::helper('reviews')->__('tags'),
            'style' => 'width:700px;',
            'after_element_html' => Mage::helper('reviews')->__('Use space or comma as separators'),
        ));

        try {
            $config = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
            $config->setData(Mage::helper('reviews')->recursiveReplace(
                            '/reviews_admin/',
                            '/' . (string) Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName') . '/',
                            $config->getData()
                    )
            );
        } catch (Exception $ex) {
            $config = null;
        }

        if (Mage::getStoreConfig('reviews/reviews/useshortcontent')) {
            $fieldset->addField('short_content', 'editor', array(
                'name' => 'short_content',
                'label' => Mage::helper('reviews')->__('Short Content'),
                'title' => Mage::helper('reviews')->__('Short Content'),
                'style' => 'width:700px; height:100px;',
                'config' => $config,
            ));
        }
        $fieldset->addField('post_content', 'editor', array(
            'name' => 'post_content',
            'label' => Mage::helper('reviews')->__('Content'),
            'title' => Mage::helper('reviews')->__('Content'),
            'style' => 'width:700px; height:500px;',
            'config' => $config
        ));

        if (Mage::getSingleton('adminhtml/session')->getReviewsData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getReviewsData());
            Mage::getSingleton('adminhtml/session')->setReviewsData(null);
        } elseif (Mage::registry('reviews_data')) {
            Mage::registry('reviews_data')->setTags(Mage::helper('reviews')->convertSlashes(Mage::registry('reviews_data')->getTags()));
            $form->setValues(Mage::registry('reviews_data')->getData());
        }
        return parent::_prepareForm();
    }

}
