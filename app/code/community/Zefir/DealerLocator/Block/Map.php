<?php

/**
 * Block with map
 *
 * @author  Zefiryn
 * @package Zefir_DealerLocator
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_DealerLocator_Block_Map extends Mage_Core_Block_Template {

    const XPATH_GOOGLE_MAPS_API_KEY = 'dealers/googlemaps/api_key';

    public function getApiKey() {
        return Mage::getStoreConfig(self::XPATH_GOOGLE_MAPS_API_KEY);
    }

    public function getMapCenterLat() {
        $position = $this->_getHelper()->getShopsCenters();
        if(array_key_exists('lat', $position)) {
            return $position['lat'];
        }
    }

    public function getMapCenterLng() {
        $position = $this->_getHelper()->getShopsCenters();
        if(array_key_exists('lng', $position)) {
            return $position['lng'];
        }
    }

    protected function _getHelper() {
        return Mage::helper('zefir_locator');
    }


} 