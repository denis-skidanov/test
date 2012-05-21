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
 * @package    Den_Gloss
 * @copyright  Copyright (c) 2009-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-L.txt
 */

class Den_Gloss_Block_Manage_Cat_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
      $collection = Mage::getModel('gloss/cat')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('cat_id', array(
          'header'    => Mage::helper('gloss')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'cat_id',
      ));

	  $this->addColumn('title', array(
          'header'    => Mage::helper('gloss')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
	  
	  $this->addColumn('identifier', array(
          'header'    => Mage::helper('gloss')->__('Identifier'),
          'align'     =>'left',
          'index'     => 'identifier',
      ));
	  
	  $this->addColumn('sort_order', array(
          'header'    => Mage::helper('gloss')->__('Sort Order'),
          'align'     => 'left',
		  'width'     => '50px',
          'index'     => 'sort_order',
      ));

      $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('gloss')->__('Action'),
                'width'     => '100px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
					array(
                        'caption'   => Mage::helper('gloss')->__('Delete'),
                        'url'       => array('base'=> '*/*/delete'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		//$this->addExportType('*/*/exportCsv', Mage::helper('gloss')->__('CSV'));
		//$this->addExportType('*/*/exportXml', Mage::helper('gloss')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('cat_id');
        $this->getMassactionBlock()->setFormFieldName('gloss');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('gloss')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('gloss')->__('Are you sure?')
        ));

        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
