<?php

/**
 * Status source class
 *
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Model_Source_Status extends Mage_Core_Model_Abstract {

    const ZEFIR_DEALER_STATUS_ENABLED = 1;
    const ZEFIR_DEALER_STATUS_DISABLED = 0;

    public function toOptionHash() {
        return array(
            self::ZEFIR_DEALER_STATUS_ENABLED => Mage::helper('zefir_dealers')->__('Enabled'),
            self::ZEFIR_DEALER_STATUS_DISABLED => Mage::helper('zefir_dealers')->__('Disabled')
        );
    }
}