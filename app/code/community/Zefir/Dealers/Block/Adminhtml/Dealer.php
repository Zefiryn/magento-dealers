<?php
/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Dealer extends Mage_Adminhtml_Block_Widget_Grid_Container {

  public function __construct() {
    $this->_controller = 'adminhtml_dealer';
    $this->_blockGroup = 'zefir_dealers';
    $this->_headerText = Mage::helper('zefir_dealers')->__('Dealers Manager');
    $this->_addButtonLabel = Mage::helper('zefir_dealers')->__('Add Dealer');
    parent::__construct();
  }

}