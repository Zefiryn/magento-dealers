<?php
/**
 * Geo Localization controller
 * 
 * @author  Zefiryn
 * @package Zefir_DealerLocate
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */

class Zefir_DealerLocator_Adminhtml_DealerlocationController extends Mage_Adminhtml_Controller_Action {

  /**
   * Mass recalculate geo position
   */
  public function massRefreshAction() {
    $dealerIds = $this->getRequest()->getParam('dealer_id');
    if (!is_array($dealerIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zefir_locator')->__('Please select dealer(s).'));
    } 
    else {
      $dealer = Mage::getModel('zefir_dealers/dealer');
      foreach ($dealerIds as $dealerId) {
        $dealer->load($dealerId)->setAutoCoordinates(1)->save();
      }
      try {
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zefir_locator')->__('Total of %d dealer(s) were refreshed.', count($dealerIds)));
      } 
      catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
    $this->_redirect('*/dealers/index');
  }
}