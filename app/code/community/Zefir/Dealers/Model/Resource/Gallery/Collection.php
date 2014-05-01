<?php
/**
 * Base dealer gallery resource model collection class
 *
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Model_Resource_Gallery_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

  /**
   * Define resource model
   *
   */
  protected function _construct() {
    $this->_init('zefir_dealers/gallery');
    parent::_construct();
  }

}