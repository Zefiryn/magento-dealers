<?php

/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Dealer_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid {

  public function __construct() {
    parent::__construct();
    $this->setId('products_grid');
    $this->setUseAjax(TRUE);
    $this->setDefaultSort('entity_id');
    $this->setSaveParametersInSession(FALSE);
  }

  protected function _addColumnFilterToCollection($column) {
    // Set custom filter for in products flag
    if ($column->getId() == 'in_products') {
      $productIds = $this->_getSelectedProducts();
      if (empty($productIds)) {
        $productIds = 0;
      }
      if ($column->getFilter()->getValue()) {
        $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
      } elseif (!empty($productIds)) {
        $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
      }
    } else {
      parent::_addColumnFilterToCollection($column);
    }
    return $this;
  }

  protected function _prepareCollection() {
    $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id');

    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns() {
    $this->addColumn('in_products', array(
        'header_css_class' => 'a-center',
        'type' => 'checkbox',
        'name' => 'in_products',
        'values' => $this->_getSelectedProducts(),
        'align' => 'center',
        'index' => 'entity_id'
    ));

    $this->addColumn('entity_id', array(
        'header' => Mage::helper('catalog')->__('ID'),
        'width' => '50px',
        'type' => 'number',
        'index' => 'entity_id'
    ));

    $this->addColumn('name', array(
        'header' => Mage::helper('catalog')->__('Name'),
        'index' => 'name'
    ));

    $this->addColumn('sku', array(
        'header' => Mage::helper('catalog')->__('SKU'),
        'width' => '80px',
        'index' => 'sku'
    ));

    $this->addColumn('type', array(
        'header' => Mage::helper('catalog')->__('Type'),
        'width' => '60px',
        'index' => 'type_id',
        'type' => 'options',
        'options' => Mage::getSingleton('catalog/product_type')->getOptionArray()
    ));

    $stockOptions = array();
    foreach (Mage::getSingleton('cataloginventory/source_stock')->toOptionArray() as $option) {
      $stockOptions[$option['value']] = $option['label'];
    }

    $this->addColumn('is_in_stock', array(
        'header' => Mage::helper('cataloginventory')->__('Stock'),
        'width' => '60px',
        'index' => 'is_in_stock',
        'type' => 'select',
        'options' => $stockOptions,
        'editable' => TRUE,
        'edit_only'	=> TRUE
    ));


    return parent::_prepareColumns();
  }

  public function getGridUrl() {
    if ($this->_getData('grid_url')) {
      return $this->_getData('grid_url');
    }
    return $this->getUrl('*/*/productsgrid', array('_current' => TRUE));
  }

  public function getRowUrl($item) {
    return '#';
  }

  protected function _getSelectedProducts() {   // Used in grid to return selected customers values.
    $products = $this->getProducts();
    if (!is_array($products)) {
      $products = array_keys($this->getSelectedProducts());
    }
    return $products;
  }

  public function getSelectedProducts() {
    $dealer_id = $this->getRequest()->getParam('id');
    $dealer = Mage::getModel('zefir_dealers/dealer')->load($dealer_id);

    $arr = array();
    return $arr;
  }

}