<?php
/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Dealer_Grid extends Mage_Adminhtml_Block_Widget_Grid {

  public function __construct() {
    parent::__construct();
    $this->setId('dealersGrid');
    // This is the primary key of the database
    $this->setDefaultSort('dealer_id');
    $this->setDefaultDir('ASC');
    $this->setSaveParametersInSession(true);
    $this->setUseAjax(true);
  }

  protected function _prepareCollection() {
    $collection = Mage::getModel('zefir_dealers/dealer')->getCollection();
    //$collection->addExpressionFieldToSelect('address', 'CONCAT(street, " ", city, " ", country_id)');
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }
  
  protected function _prepareMassaction() {
    $this->setMassactionIdField('dealer_id');
    $this->getMassactionBlock()->setFormFieldName('dealer_id');
    
    //add delete mass action option
    $this->getMassactionBlock()->addItem('delete', array(
      'label'=> Mage::helper('tax')->__('Delete'),
      'url'  => $this->getUrl('*/*/massDelete', array('' => '')),
      'confirm' => Mage::helper('tax')->__('Are you sure?')
    ));
    
    //add status update mass action
    $statuses = Mage::getSingleton('zefir_dealers/source_status')->toOptionHash();

    array_unshift($statuses, array('label'=>'', 'value'=>''));
    $this->getMassactionBlock()->addItem('status', array(
         'label'=> Mage::helper('zefir_dealers')->__('Change status'),
         'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
         'additional' => array(
                'visibility' => array(
                     'name' => 'status',
                     'type' => 'select',
                     'class' => 'required-entry',
                     'label' => Mage::helper('zefir_dealers')->__('Status'),
                     'values' => $statuses
                 )
         )
    ));
    
    Mage::dispatchEvent('zefir_dealers_grid_prepare_massaction', array('grid' => $this));
    return $this;
  }

  protected function _prepareColumns() {
    
    $this->addColumn('dealer_id', array(
        'header' => Mage::helper('zefir_dealers')->__('Dealer ID'),
        'align' => 'right',
        'width' => '50px',
        'index' => 'dealer_id',
    ));
    
    $this->addColumn('dealer_code', array(
        'header' => Mage::helper('zefir_dealers')->__('Dealer Code'),
        'align' => 'left',
        'width' => '50px',
        'index' => 'dealer_code',
    ));
    
    $this->addColumn('dealer_name', array(
        'header' => Mage::helper('zefir_dealers')->__('Dealer Name'),
        'align' => 'left',
        'width' => '150px',
        'index' => 'dealer_name',
    ));
    $this->addColumn('street', array(
        'header' => Mage::helper('zefir_dealers')->__('Street'),
        'align' => 'left',
        'width' => '75px',
        'index' => 'street',
    ));
    $this->addColumn('city', array(
        'header' => Mage::helper('zefir_dealers')->__('City'),
        'align' => 'left',
        'width' => '50px',
        'index' => 'city',
    ));
    $countries = array();
    foreach(Mage::getResourceModel('directory/country_collection')->toOptionArray(false) as $country) {
      $countries[$country['value']] = $country['label'];
    }
    $this->addColumn('country_id', array(
        'header' => Mage::helper('zefir_dealers')->__('Country'),
        'align' => 'left',
        'width' => '100px',
        'renderer' => 'zefir_dealers/adminhtml_dealer_renderer_country',
        'index' => 'country_id',
        'type' => 'options',
        'options' => $countries
    ));
    $this->addColumn('status', array(
        'header' => Mage::helper('zefir_dealers')->__('Status'),
        'align' => 'left',
        'width' => '50px',
        'index' => 'status',
        'type'  => 'options',
        'options' => Mage::getSingleton('zefir_dealers/source_status')->toOptionHash()
    ));

    $this->addColumn('action', array(
        'header'   => $this->helper('zefir_dealers')->__('Action'),
        'width'    => 15,
        'sortable' => false,
        'filter'   => false,        
        'getter'   => 'getDealerId',
        'type'     => 'action',
        'actions'  => array(
            array(
                'url'     => array('base' => '*/*/edit'),
                'field'   => 'id',
                'caption' => $this->helper('zefir_dealers')->__('Edit'),
            ),
        )
    ));

    Mage::dispatchEvent('zefir_dealers_grid_prepare_columns', array('grid' => $this));
    return parent::_prepareColumns();
  }

  public function getRowUrl($row) {
    return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

  public function getGridUrl() {
    return $this->getUrl('*/*/grid', array('_current' => true));
  }

}
