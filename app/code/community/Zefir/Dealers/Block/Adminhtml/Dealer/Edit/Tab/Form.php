<?php

/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Dealer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

  public function __construct() {
    parent::__construct();
    $this->setTemplate('zefir/dealers/tab/address_form.phtml');
  }

  public function getRegionsUrl() {
    return $this->getUrl('*/json/countryRegion');
  }

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

    $addresFieldset = $form->addFieldset('address_form', array('legend' => Mage::helper('zefir_dealers')->__('Address')));
    $addresFieldset->addField('street', 'text', array(
        'label' => Mage::helper('zefir_dealers')->__('Street'),
        'class' => '',
        'required' => true,
        'name' => 'street',
    ));
    $addresFieldset->addField('city', 'text', array(
        'label' => Mage::helper('zefir_dealers')->__('City'),
        'class' => '',
        'required' => true,
        'name' => 'city',
    ));
    $addresFieldset->addField('zipcode', 'text', array(
        'label' => Mage::helper('zefir_dealers')->__('Zip Code'),
        'class' => '',
        'required' => true,
        'name' => 'zipcode',
    ));
    
    $isRequired = Mage::helper('directory')->isRegionRequired(Mage::registry('dealer_data')->getCountryId());
    $addresFieldset->addField('region', 'text', array(
        'label' => Mage::helper('zefir_dealers')->__('State/Province'),
        'class' => '',
        'required' => $isRequired,
        'name' => 'region',
    ));
    /**
     * addField method set default renderer. We need to override it after field is created
     */
    $form->getElement('region')->setRenderer(Mage::getModel('zefir_dealers/renderer_region'));
        
    $addresFieldset->addField('region_id', 'hidden', array(
        'label' => Mage::helper('zefir_dealers')->__('State/Province'),
        'class' => '',
        'required' => false,
        'name' => 'region_id',
    ));
    $regionElement = $form->getElement('region_id');
    if ($regionElement) {
      $regionElement->setNoDisplay(true);
    }
    $addresFieldset->addField('country_id', 'select', array(
        'label' => Mage::helper('zefir_dealers')->__('Country'),
        'class' => 'input-text required-entry countries',
        'required' => true,
        'name' => 'country_id',
        'values' => Mage::getResourceModel('directory/country_collection')->toOptionArray()
    ));

    //allow other modules add fields to the form
    Mage::dispatchEvent('zefir_dealer_prepare_form_fields', array('form' => $this->getForm()));

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
  
  /**
     * Return JSON object with countries associated to possible websites
     *
     * @return string
     */
    public function getDefaultCountriesJson() {
        $websites = Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(false, true);
        $result = array();
        foreach ($websites as $website) {
            $result[$website['value']] = Mage::app()->getWebsite($website['value'])->getConfig(
                Mage_Core_Helper_Data::XML_PATH_DEFAULT_COUNTRY
            );
        }

        return Mage::helper('core')->jsonEncode($result);
    }

}