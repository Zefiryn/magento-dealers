<?php 
/**
 * Base dealer model class
 * 
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Model_Dealer extends Mage_Core_Model_Abstract {
  
  protected $_eventPrefix = 'dealer';
  protected $_eventObject = 'dealer';


  public function _construct() {    
    $this->_init('zefir_dealers/dealer');
    parent::_construct();
  }
  
  public function getId() {
    return $this->getDealerId();    
  }
  
}
