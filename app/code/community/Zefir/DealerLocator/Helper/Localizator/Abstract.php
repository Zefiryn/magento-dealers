<?php
/**
 * General class for third party geocode api
 * 
 * @author  Zefiryn
 * @package Zefir_DealerLocator
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */

class Zefir_DealerLocator_Helper_Localizator_Abstract extends Mage_Core_Helper_Abstract {
  
  /**
   * Request type
   * 
   * @const string
   */  
  const METHOD = Zend_Http_Client::GET;
  
  /**
   * URI of the service
   * @const string
   */
  const URI = '';
  
  /**
   * Parameters for the request
   * @var array
   */
  protected $_params = array();
  
  /**
   * Make request to the webservice
   * 
   * @return Zend_Http_Response|boolean
   */
  public function makeRequest() {
    $client = new Zend_Http_Client();
    $client->setUri($this->_getUri());
    $client->setMethod($this->_getMethod());    
    foreach($this->_getParameters() as $key => $val) {
      if (self::METHOD == Zend_Http_Client::GET) {
        $client->setParameterGet($key, $val);
      }
      if (self::METHOD == Zend_Http_Client::POST) {
        $client->setParameterPost($key, $val);
      }
    }
    
    $response = $client->request();
    
    if ($response->isSuccessful() && $response->getStatus() == 200) {
      return $response;      
    }    
    return false;    
  }
  
  /**
   * Get coordinates for the given data
   * 
   * @param array $data
   * @return WSNYC_Vendor_Helper_Localizator_Abstract
   */
  public function getCoordinates($data) {
    
    $this->_prepareRequest($data);
    $response = $this->makeRequest();        
    if ($response) {
      return $this->_parseResponse($response);
    }
    
    return $this;
  }
  
  /**
   * Prepare data for the request
   * 
   * @param array $data
   * @return WSNYC_Vendor_Helper_Localizator_Abstract
   */
  protected function _prepareRequest($data) {
    $this->_params = $data;
    return $this;
  }
  
  /**
   * Get parameters for the request
   * 
   * @return array
   */
  protected function _getParameters() {
    return $this->_params;
  }
  
  /**
   * Parse request from the webservice
   * 
   * @param Zend_Http_Response $response
   * @return boolean
   */
  protected function _parseResponse($response) {
    return false;
  }
  
  /**
   * Get URI address for fetching data
   * 
   * @return string
   */
  protected function _getUri(){
    $class = get_called_class();
    //URI const can be overwritten in child class without need of overwriting getter method
    return $class::URI;
  }
  
  /**
   * Get request method
   * 
   * @return string
   */
  protected function _getMethod(){
    $class = get_called_class();
    return $class::METHOD;
  }
  
}

