<?php
/**
 * Helper for fetching data using GoogleMaps API
 * 
 * @author  Zefiryn
 * @package Zefir_DealerLocator
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */

class Zefir_DealerLocator_Helper_Localizator_Google extends Zefir_DealerLocator_Helper_Localizator_Abstract {
  
  /**
   * API URI
   * @const string
   */
  const URI = 'http://maps.googleapis.com/maps/api/geocode/json'; 
  
  /**
   * Prepare request data
   * 
   * @param array $data
   * @return \Zefir_Dealers_Helper_Localizator_Abstract
   */
  protected function _prepareRequest($data) {
    $params = array('address' => null, 'sensor' => 'false');
    if (isset($data['street']) && !empty($data['city'])) {
      $params['address'] .= $data['street'].', ';
    }
    if (isset($data['city']) && !empty($data['city'])) {
      $params['address'] .= $data['city'].', ';;
    }
    if (isset($data['region']) && !empty($data['region'])) {
      $params['address'] .= $data['region'].', ';
    }
    if (isset($data['region_id']) && !empty($data['region_id'])) {
      $region = Mage::getModel('directory/region')->load($data['region_id']);
      $params['address'] .= $region->getName().', ';
    }
    if (isset($data['zipcode']) || isset($data['zip'])) {
      $params['address'] .= isset($data['zipcode']) ? $data['zipcode'] : $data['zip'];
      $params['address'] .= ', ';
    }
    if (isset($data['country'])) {
      $params['address'] .= $data['country'];
    }
    
    return parent::_prepareRequest($params);
  }
  
  /**
   * Retrieve latitude and longitude from the API response
   * 
   * @param Zend_Http_Response|boolean $response
   * @return array|boolean
   */
  protected function _parseResponse($response) {
    
    if ($response->isSuccessful() && $response->getStatus() == 200) {
      $_response = json_decode($response->getBody());      
      $_coordinates = $_response->results[0]->geometry->location;
      $geo = array('lat' => $_coordinates->lat, 'lng' => $_coordinates->lng);
      return $geo;
    }
    
    return false;
  }
}

