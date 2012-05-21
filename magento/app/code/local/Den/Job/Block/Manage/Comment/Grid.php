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
class Den_Job_Block_Manage_Comment_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        
        parent::__construct();
        $this->setId('commentGrid');
        $this->setDefaultSort('main_table.status');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        
        $collection = Mage::getModel('job/comment')->getCollection();
        $collection->getSelect()->joinLeft(array('job_job_main' => $collection->getTable('job/job')), 'main_table.post_id=job_job_main.post_id', array('job_job_main.title'));
 
        $this->setCollection($collection); 
        
        
        return parent::_prepareCollection(); 
        
    }

    protected function _prepareColumns() {
         
        
        $this->addColumn('comment_id', array(
            'header' => Mage::helper('job')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'comment_id',
        ));   

        $this->addColumn('comment', array(
            'header' => Mage::helper('job')->__('Comment'),
            'align' => 'left',
            'index' => 'comment',
        ));


        $this->addColumn('user', array(
            'header' => Mage::helper('job')->__('Poster'),
            'width' => '150px',
            'index' => 'user',
        ));


        $this->addColumn('email', array(
            'header' => Mage::helper('job')->__('Email Address'),
            'width' => '150px',
            'index' => 'email',
        ));
	
	 $this->addColumn('tel', array(
            'header' => Mage::helper('partners')->__('Telefon'),
            'width' => '150px',
            'index' => 'tel',
        ));

        $this->addColumn('created_time', array(
            'header' => Mage::helper('job')->__('Created'),
            'align' => 'center',
            'width' => '120px',
            'type' => 'date',
            'default' => '--',
            'index' => 'created_time',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('job')->__('Status'),
            'align' => 'canter',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Unapproved',
                2 => 'Approved',
            ),
        ));



        $this->addColumn('title', array(
            'header' => Mage::helper('job')->__('Post Title'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'title',
            'type' => 'text'
        ));

        $this->addColumn('job_job_main.post_id', array(
            'header' => Mage::helper('job')->__('Link to Post'),
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getPostId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('job')->__('View'),
                    'url' => array(
                        'base' => '*/manage_job/edit'
                    ),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('job')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('job')->__('Approve'),
                    'url' => array('base' => '*/*/approve'),
                    'field' => 'id'
                ),
                array(
                    'caption' => Mage::helper('job')->__('Unapprove'),
                    'url' => array('base' => '*/*/unapprove'),
                    'field' => 'id'
                ),
                array(
                    'caption' => Mage::helper('job')->__('Delete'),
                    'url' => array('base' => '*/*/delete'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));


        //$this->addExportType('*/*/exportCsv', Mage::helper('job')->__('CSV'));
        //$this->addExportType('*/*/exportXml', Mage::helper('job')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        
        $this->setMassactionIdField('main_table.comment_id');
        $this->getMassactionBlock()->setFormFieldName('comment');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('job')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('job')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('approve', array(
            'label' => Mage::helper('job')->__('Approve'),
            'url' => $this->getUrl('*/*/massApprove'),
            'confirm' => Mage::helper('job')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('unapprove', array(
            'label' => Mage::helper('job')->__('Unapprove'),
            'url' => $this->getUrl('*/*/massUnapprove'),
            'confirm' => Mage::helper('job')->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
