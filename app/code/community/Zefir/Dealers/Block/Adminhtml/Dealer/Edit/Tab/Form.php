<?php
/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Dealer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

  protected function _prepareForm() {
    $form = new Varien_Data_Form();
    $this->setForm($form);
    $fieldset = $form->addFieldset('general_form', array('legend' => Mage::helper('zefir_dealers')->__('General')));

    $fieldset->addField('dealer_id', 'hidden', array('name' => 'dealer_id',));
    
    $fieldset->addField('dealer_code', 'text', array(
        'label' => Mage::helper('zefir_dealers')->__('Dealer Code'),
        'class' => 'required-entry',
        'required' => true,
        'name' => 'dealer_code',
    ));
    $fieldset->addField('dealer_name', 'text', array(
        'label' => Mage::helper('zefir_dealers')->__('Dealer Name'),
        'class' => 'required-entry',
        'required' => true,
        'name' => 'dealer_name',
    ));
    $fieldset->addField('status', 'select', array(
        'label' => Mage::helper('zefir_dealers')->__('Status'),
        'title' => Mage::helper('zefir_dealers')->__('Status'),
        'name' => 'status',
        'required' => true,
        'options' => Mage::getSingleton('zefir_dealers/source_status')->toOptionHash()
    ));
    
    if (Mage::getSingleton('adminhtml/session')->getDealerData()) {
      $form->setValues(Mage::getSingleton('adminhtml/session')->getDealerData());
    } 
    elseif (Mage::registry('dealer_data')) {
      if (!Mage::registry('dealer_data')->getId()) {
        Mage::registry('dealer_data')->setStatus(1);
      }
      $form->setValues(Mage::registry('dealer_data')->getData());
    }


    return parent::_prepareForm();
  }

}