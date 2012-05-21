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

class Den_Reviews_Block_Manage_Reviews_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('reviewsGrid');
		$this->setDefaultSort('created_time');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
	
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('reviews/reviews')->getCollection();
		$store = $this->_getStore();
		if ($store->getId()) {
            $collection->addStoreFilter($store);
		}
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn('post_id', array(
		  'header'    => Mage::helper('reviews')->__('ID'),
		  'align'     =>'right',
		  'width'     => '50px',
		  'index'     => 'post_id',
		));
		
		$this->addColumn('title', array(
		  'header'    => Mage::helper('reviews')->__('Title'),
		  'align'     =>'left',
		  'index'     => 'title',
		));
		
		$this->addColumn('identifier', array(
		  'header'    => Mage::helper('reviews')->__('Identifier'),
		  'align'     => 'left',
		  'index'     => 'identifier',
		));
		
		$this->addColumn('user', array(
			'header'    => Mage::helper('reviews')->__('Poster'),
			'width'     => '150px',
			'index'     => 'user',
		));
        
        
         $this->addColumn('created_time',
                array(
                    'header' => Mage::helper('reviews')->__('Created at'),
                    'index' => 'created_time',
                    'type' => 'datetime',
                    'width' => '120px',
                    'gmtoffset' => true,
                    'default' => ' -- '
         ));
         
         $this->addColumn('update_time',
                array(
                    'header' => Mage::helper('reviews')->__('Updated at'),
                    'index' => 'update_time',
                    'width' => '120px',
                    'type' => 'datetime',
                    'gmtoffset' => true,
                    'default' => ' -- '
         ));
		
	  
		
		$this->addColumn('status', array(
		  'header'    => Mage::helper('reviews')->__('Status'),
		  'align'     => 'left',
		  'width'     => '80px',
		  'index'     => 'status',
		  'type'      => 'options',
		  'options'   => array(
			  1 => Mage::helper('reviews')->__('Enabled'),
			  2 => Mage::helper('reviews')->__('Disabled'),
			  3 => Mage::helper('reviews')->__('Hidden'),
		  ),
		));
		
		$this->addColumn('action',
			array(
				'header'    =>  Mage::helper('reviews')->__('Action'),
				'width'     => '100',
				'type'      => 'action',
				'getter'    => 'getId',
				'actions'   => array(
					array(
						'caption'   => Mage::helper('reviews')->__('Edit'),
						'url'       => array('base'=> '*/*/edit'),
						'field'     => 'id'
					)
				),
				'filter'    => false,
				'sortable'  => false,
				'index'     => 'stores',
				'is_system' => true,
		));
		
		return parent::_prepareColumns();
	}

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('post_id');
        $this->getMassactionBlock()->setFormFieldName('reviews');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('reviews')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('reviews')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('reviews/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('reviews')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('reviews')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

}