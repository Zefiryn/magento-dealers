<?php 
/**
 * Base dealer model class
 * 
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Model_Dealer extends Mage_Core_Model_Abstract {
  
  /**
   * Event prefix name used in magento object related events
   * 
   * @var string
   */
  protected $_eventPrefix = 'dealer';
  
  /**
   * Event argument name
   * 
   * @var string
   */
  protected $_eventObject = 'dealer';


  /**
   * Constructor function
   */
  public function _construct() {    
    $this->_init('zefir_dealers/dealer');
    parent::_construct();
  }
  
  /**
   * Get dealer ID
   * 
   * @return type
   */
  public function getId() {
    return $this->getDealerId();    
  }
  
  /**
   * Save dealer products
   * Argument is an array with the following structure
   * array( 
   *    [product_id] => array( [is_in_stock] => 1|0 ),
   *    ...
   * )
   * 
   * @todo add actual saving procedure
   * @param array $links
   */
  public function saveProducts($links) {
    return $this;
  }
    
}
