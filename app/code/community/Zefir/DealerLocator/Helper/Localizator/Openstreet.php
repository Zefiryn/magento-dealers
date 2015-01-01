<?php

/**
 * Helper for fetching data using OpenStreetMaps API
 *
 * @author  Zefiryn
 * @package Zefir_DealerLocator
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_DealerLocator_Helper_Localizator_Openstreet extends Zefir_DealerLocator_Helper_Localizator_Abstract {

    /**
     * API URI
     *
     * @const string
     */
    const URI = 'http://nominatim.openstreetmap.org/search';

    /**
     * OpenStreetMap response format
     *
     * @const string
     */
    const RESPONSE_FORMAT = 'json';

    /**
     * Prepare request data
     *
     * @param array $data
     * @return \Zefir_Dealers_Helper_Localizator_Abstract
     */
    protected function _prepareRequest($data) {

        $params = array();
        if(isset($data['street']) && !strstr($data['street'], 'P.O.')) {
            $params['street'] = $data['street'];
        }
        if(isset($data['city']) && isset($data['country']) && $data['country'] != 'US') {
            $params['city'] = $data['city'];
        }
        if(isset($data['region'])) {
            $params['county'] = $data['region'];
        }
        elseif(isset($data['region_id'])) {
            $region = Mage::getModel('directory/region')->load($data['region_id']);
            $params['county'] = $region->getName();
        }
        if(isset($data['zipcode']) || isset($data['zip'])) {
            $params['postalcode'] = isset($data['zipcode']) ? $data['zipcode'] : $data['zip'];
        }
        if(isset($data['country'])) {
            $params['country'] = $data['country'];
        }
        $params['format'] = $this->_getFormat();

        return parent::_prepareRequest($params);
    }

    /**
     * Get response format
     *
     * @return string
     */
    protected function _getFormat() {
        return self::RESPONSE_FORMAT;
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
            if(is_array($_response)) {
                $_response = array_shift($_response);
            }
            if($_response) {
                $geo = array('lat' => $_response->lat, 'lng' => $_response->lon);
            }
            else {
                $geo = false;
            }

            return $geo;
        }

        return false;
    }
}

