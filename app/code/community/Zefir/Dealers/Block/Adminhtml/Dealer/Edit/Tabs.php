<?php
/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Dealer_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

  public function __construct() {
    parent::__construct();
    $this->setId('dealer_tabs');
    $this->setDestElementId('edit_form');
    $this->setTitle(Mage::helper('zefir_dealers')->__('General'));
  }

  protected function _beforeToHtml() {
    $this->addTab('form_section', array(
        'label' => Mage::helper('zefir_dealers')->__('General Information'),
        'title' => Mage::helper('zefir_dealers')->__('General Information'),
        'content' => $this->getLayout()->createBlock('zefir_dealers/adminhtml_dealer_edit_tab_form')->toHtml(),
    ));

    return parent::_beforeToHtml();
  }

}