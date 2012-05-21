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

class Den_Stock_Block_Manage_Cat_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('commentGrid');
      $this->setDefaultSort('sort_order');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
	  
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('stock/cat')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('cat_id', array(
          'header'    => Mage::helper('stock')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'cat_id',
      ));

	  $this->addColumn('title', array(
          'header'    => Mage::helper('stock')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
	  
	  $this->addColumn('identifier', array(
          'header'    => Mage::helper('stock')->__('Identifier'),
          'align'     =>'left',
          'index'     => 'identifier',
      ));
	  
	  $this->addColumn('sort_order', array(
          'header'    => Mage::helper('stock')->__('Sort Order'),
          'align'     => 'left',
		  'width'     => '50px',
          'index'     => 'sort_order',
      ));

      $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('stock')->__('Action'),
                'width'     => '100px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
					array(
                        'caption'   => Mage::helper('stock')->__('Delete'),
                        'url'       => array('base'=> '*/*/delete'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		//$this->addExportType('*/*/exportCsv', Mage::helper('stock')->__('CSV'));
		//$this->addExportType('*/*/exportXml', Mage::helper('stock')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('cat_id');
        $this->getMassactionBlock()->setFormFieldName('stock');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('stock')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('stock')->__('Are you sure?')
        ));

        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
