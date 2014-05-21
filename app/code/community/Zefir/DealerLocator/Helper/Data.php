<?php
/**
 * Geolocation helper class
 * 
 * @author  Zefiryn
 * @package Zefir_DealerLocator
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */

class Zefir_DealerLocator_Helper_Data extends Mage_Core_Helper_Abstract {

  protected $_centerPosition;

  /**
   * Return selected localizator helper
   *
   * @return \Zefir_DealerLocator_Helper_Localizator_Abstract
   */
  public function getLocalizator() {
    $helperClass = 'zefir_locator/localizator_' . Mage::getStoreConfig('dealers/localization/geolocation_service');
    return Mage::helper($helperClass);    
  }

  /**
   * Check if localization module is enabled
   * @return boolean
   */
  public function isLocalizatorEnabled() {
    return Mage::getStoreConfig('dealers/localization/geolocation_enable');
  }

  /**
   * Calculate average latitude and longitude of all dealers
   */
  public function getShopsCenters() {
    if (null == $this->_centerPosition) {
      $collection = Mage::getModel('zefir_dealers/dealer')
                          ->getCollection()
                            ->addStatusFilter(Zefir_Dealers_Model_Source_Status::ZEFIR_DEALER_STATUS_ENABLED);

      $collection->load();
      $size = $collection->count();
      $latitudes = $collection->getColumnValues('lat');
      $longitudes = $collection->getColumnValues('lng');

      $this->_centerPosition = array(
                                  'lat' => array_sum($latitudes) / $size,
                                  'lng' => array_sum($longitudes) / $size
                                );
    }

    return $this->_centerPosition;
  }
}