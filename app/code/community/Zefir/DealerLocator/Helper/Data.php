<?php
/**
 * Geolocation helper class
 * 
 * @author  Zefiryn
 * @package Zefir_DealerLocator
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */

class Zefir_DealerLocator_Helper_Data extends Mage_Core_Helper_Abstract {

  /**
   * Return selected localizator helper
   * @return \Zefir_DealerLocator_Helper_Localizator_Abstract
   */
  public function getLocalizator() {
    $helperClass = 'zefir_locator/localizator_' . Mage::getStoreConfig('dealers/localization/geolocation_service');
    return Mage::helper($helperClass);    
  }
  
  public function isLocalizatorEnabled() {
    return Mage::getStoreConfig('dealers/localization/geolocation_enable');
  }
}