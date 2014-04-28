<?php
/**
 * Dealer-Product Link resource model class
 * 
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Model_Resource_Product_Link extends Mage_Core_Model_Resource_Db_Abstract {

  /**
   * Initialize resource model
   */
  protected function _construct() {
    $this->_init('zefir_dealers/product_link', 'link_id');
  }
}