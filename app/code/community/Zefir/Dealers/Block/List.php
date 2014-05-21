<?php
/**
 * Dealers List Block
 *
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */

class Zefir_Dealers_Block_List extends Mage_Core_Block_Template {

  protected $_dealerCollection;

  public function _prepareLayout() {

  }

  public function getDealerCollection() {
    if (null === $this->_dealerCollection) {
      $collection = Mage::getModel('zefir_dealers/dealer')
                          ->getCollection()
                            ->addStatusFilter(Zefir_Dealers_Model_Source_Status::ZEFIR_DEALER_STATUS_ENABLED);
      $this->_dealerCollection = $collection;
      $this->_renderFilters();
      $this->_renderOrder();
    }

    return $this->_dealerCollection;
  }

  protected function _renderFilters() {

  }

  protected function _renderOrder() {

  }

} 