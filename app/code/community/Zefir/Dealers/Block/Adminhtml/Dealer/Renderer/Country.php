<?php

/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Dealer_Renderer_Country extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    protected $_countries = array();

    public function render(Varien_Object $row) {
        return $this->_getCountry($row->getData($this->getColumn()->getIndex()));
    }

    protected function _getCountry($code) {
        if(!array_key_exists($code, $this->_countries)) {
            $this->_countries[$code] = Mage::getModel('directory/country')->loadByCode($code)->getName();
        }

        return $this->_countries[$code];
    }

}