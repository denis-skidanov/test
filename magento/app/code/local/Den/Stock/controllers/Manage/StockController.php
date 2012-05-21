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
class Den_Stock_Manage_StockController extends Mage_Adminhtml_Controller_Action {

    public function preDispatch() {
        parent::preDispatch();
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('admin/stock/posts');
    }

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('stock/posts');

        return $this;
    }

    public function indexAction() {

        $this->displayTitle('Posts');


        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('stock/stock')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('stock_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('stock/posts');
            $this->displayTitle('Edit post');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('stock/manage_stock_edit'))
                    ->_addLeft($this->getLayout()->createBlock('stock/manage_stock_edit_tabs'));

            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('stock')->__('Post does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('stock/stock')->load($id);

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('stock_data', $model);

        $this->loadLayout();
        $this->_setActiveMenu('stock/posts');
        $this->displayTitle('Add new post');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('stock/manage_stock_edit'))
                ->_addLeft($this->getLayout()->createBlock('stock/manage_stock_edit_tabs'));

        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);

        $this->renderLayout();
    }

    public function duplicateAction() {
        $oldIdentifier = $this->getRequest()->getParam('identifier');
        $i = 1;
        $newIdentifier = $oldIdentifier . $i;
        while (Mage::getModel('stock/post')->loadByIdentifier($newIdentifier)->getData())
            $newIdentifier = $oldIdentifier . ++$i;
        $this->getRequest()->setPost('identifier', $newIdentifier);
        $this->_forward('save');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('stock/post');
            if (isset($data['tags'])) {
                if ($this->getRequest()->getParam('id')) {
                    $model->load($this->getRequest()->getParam('id'));
                    $originalTags = explode(",", $model->getTags());
                } else {
                    $originalTags = array();
                }

                $tags = preg_split("/[,    ]+\s*/i", $data['tags'], -1, PREG_SPLIT_NO_EMPTY);

                foreach ($tags as $key => $tag) {
                    $tags[$key] = Mage::helper('stock')->convertSlashes($tag, 'forward');
                }
                $tags = array_unique($tags);



                $commonTags = array_intersect($tags, $originalTags);
                $removedTags = array_diff($originalTags, $commonTags);
                $addedTags = array_diff($tags, $commonTags);

                if (count($tags)) {
                    $data['tags'] = trim(implode(',', $tags));
                } else {
                    $data['tags'] = '';
                }
            }
            if (isset($data['stores'])) {
                if ($data['stores'][0] == 0) {
                    unset($data['stores']);
                    $data['stores'] = array();
                    $stores = Mage::getSingleton('adminhtml/system_store')->getStoreCollection();
                    foreach ($stores as $store)
                        $data['stores'][] = $store->getId();
                }
            }


            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {

                $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
                if (isset($data['created_time']) && $data['created_time']) {
                    $dateFrom = Mage::app()->getLocale()->date($data['created_time'], $format);
                    $model->setCreatedTime(Mage::getModel('core/date')->gmtDate(null, $dateFrom->getTimestamp()));
                    $model->setUpdateTime(Mage::getModel('core/date')->gmtDate());
                } else {
                    $model->setCreatedTime(Mage::getModel('core/date')->gmtDate());
                }


                if ($this->getRequest()->getParam('user') == NULL) {
                    $model->setUser(Mage::getSingleton('admin/session')->getUser()->getFirstname() . " " . Mage::getSingleton('admin/session')->getUser()->getLastname())
                            ->setUpdateUser(Mage::getSingleton('admin/session')->getUser()->getFirstname() . " " . Mage::getSingleton('admin/session')->getUser()->getLastname());
                } else {
                    $model->setUpdateUser(Mage::getSingleton('admin/session')->getUser()->getFirstname() . " " . Mage::getSingleton('admin/session')->getUser()->getLastname());
                }

                $model->save();


                /* recount affected tags */
                if (isset($data['stores'])) {
                    $stores = $data['stores'];
                } else {
                    $stores = array(null);
                }

                $affectedTags = array_merge($addedTags, $removedTags);

                foreach ($affectedTags as $tag) {
                    foreach ($stores as $store) {
                        if (trim($tag)) {
                            Mage::getModel('stock/tag')->loadByName($tag, $store)->refreshCount();
                        }
                    }
                }




                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('stock')->__('Post was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('stock')->__('Unable to find post to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('stock/stock');
                $model->load($this->getRequest()->getParam('id'));
                $_tags = explode(',', $model->getData('tags'));
                $model->delete();
                $_stores = Mage::getSingleton('adminhtml/system_store')->getStoreCollection();
                foreach ($_tags as $tag)
                    foreach ($_stores as $store)
                        if (trim($tag)) {
                            Mage::getModel('stock/tag')->loadByName($tag, $store->getId())->refreshCount();
                        }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Post was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $stockIds = $this->getRequest()->getParam('stock');
        if (!is_array($stockIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select post(s)'));
        } else {
            try {
                foreach ($stockIds as $stockId) {
                    $stock = Mage::getModel('stock/stock')->load($stockId);
                    $stock->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($stockIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $stockIds = $this->getRequest()->getParam('stock');
        if (!is_array($stockIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select post(s)'));
        } else {
            try {

                foreach ($stockIds as $stockId) {
                    $stock = Mage::getModel('stock/stock')
                            ->load($stockId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setStores('')
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($stockIds))
                );
            } catch (Exception $e) {

                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    protected function displayTitle($data = null, $root = 'Stock') {

        if (!Mage::helper('stock')->magentoLess14()) {
            if ($data) {
                if (!is_array($data)) {
                    $data = array($data);
                }
                foreach ($data as $title) {
                    $this->_title($this->__($title));
                }
                $this->_title($this->__($root));
            } else {
                $this->_title($this->__('Stock'))->_title($root);
            }
        }
        return $this;
    }

}
