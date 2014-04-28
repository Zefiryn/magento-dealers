<?php 
/**
 * Dealer-Product Link model class
 * 
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Model_Product_Link extends Mage_Core_Model_Abstract {
  
  /**
   * Event prefix name used in magento object related events
   * 
   * @var string
   */
  protected $_eventPrefix = 'dealer_product';
  
  /**
   * Event argument name
   * 
   * @var string
   */
  protected $_eventObject = 'product_link';


  /**
   * Constructor function
   */
  public function _construct() {    
    $this->_init('zefir_dealers/product_link');
    parent::_construct();
  }
 
  /**
   * Get collection of all linked products by dealer id
   * 
   * @param integer $dealerId
   * @return Zefir_Dealers_Resource_Product_LinkCollection
   */
  public function getDealerProducts($dealerId) {    
    return $this->getCollection()->addFieldToFilter('dealer_id', array('eq' => $dealerId));
  }
}
