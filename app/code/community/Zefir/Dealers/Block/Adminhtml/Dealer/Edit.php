<?php
/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Dealer_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'zefir_dealers';
        $this->_controller = 'adminhtml_dealer';
 
        $this->_updateButton('save', 'label', Mage::helper('zefir_dealers')->__('Save Dealer'));
        $this->_updateButton('delete', 'label', Mage::helper('zefir_dealers')->__('Delete Delaer'));
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('dealer_data') && Mage::registry('dealer_data')->getId() ) {
            return Mage::helper('zefir_dealers')->__("Edit Dealer '%s'", $this->htmlEscape(Mage::registry('dealer_data')->getDealerName()));
        } else {
            return Mage::helper('zefir_dealers')->__('Add Dealer');
        }
    }
}