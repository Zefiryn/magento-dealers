<?php

/**
 * Helper for fetching data using Geonames API
 *
 * @author  Zefiryn
 * @package Zefir_DealerLocator
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_DealerLocator_Helper_Localizator_Geonames extends Zefir_DealerLocator_Helper_Localizator_Abstract {

    /**
     * API URI
     */
    const URI = 'http://api.geonames.org/postalCodeLookupJSON?';

    /**
     * Prepare request data
     *
     * @param array $data
     * @return \Zefir_Dealers_Helper_Localizator_Abstract
     */
    protected function _prepareRequest($data) {

        $params = array();
        if(isset($data['zipcode']) || isset($data['zip'])) {
            $params['postalcode'] = isset($data['zipcode']) ? $data['zipcode'] : $data['zip'];
        }
        if(isset($data['country'])) {
            $params['country'] = $data['country'];
        }
        $params['operator'] = 'AND';
        $params['isReduced'] = 'false';
        $params['username'] = Mage::getStoreConfig('dealers/geonames/username');

        return parent::_prepareRequest($params);
    }

    /**
     * Retrieve latitude and longitude from the API response
     *
     * @param Zend_Http_Response|boolean $response
     * @return array|boolean
     */
    protected function _parseResponse($response) {

        if($response->isSuccessful() && $response->getStatus() == 200) {
            $_response = json_decode($response->getBody());
            if(is_array($_response->postalcodes)) {
                $_response = array_shift($_response->postalcodes);
            }
            if($_response) {
                $geo = array('lat' => $_response->lat, 'lng' => $_response->lng);
            }
            else {
                $geo = false;
            }

            return $geo;
        }

        return false;
    }
}
