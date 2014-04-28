<?php
/**
 * Main Dealers manage controller
 * 
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */

class Zefir_Dealers_Adminhtml_DealersController extends Mage_Adminhtml_Controller_Action {

  /**
   * Basic initialization 
   * @return \Zefir_Dealers_Adminhtml_DealersController
   */
  protected function _initAction() {
    $this->loadLayout()
            ->_setActiveMenu('sales/dealers')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Dealers'), Mage::helper('adminhtml')->__('Manage Dealers'));
    return $this;
  }

  /**
   * List all dealers
   */
  public function indexAction() {

    $this->_initAction();
    $this->_addContent($this->getLayout()->createBlock('zefir_dealers/adminhtml_dealer'));
    $this->renderLayout();
  }
  
  /**
   * Create new dealer
   */
  public function newAction() {
    $this->_forward('edit');
  }

  /**
   * Show edit form for new and existing dealer
   */
  public function editAction() {
    $id = $this->getRequest()->getParam('id');
    $dealer = Mage::getModel('zefir_dealers/dealer')->load($id);

    if ($dealer->getId() || $id == 0) {

      Mage::register('dealer_data', $dealer);

      $this->_initAction();

      $this->_addBreadcrumb(Mage::helper('zefir_dealers')->__('Edit Dealer'), Mage::helper('zefir_dealers')->__('Edit Dealer'));

      $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

      $this->_addContent($this->getLayout()->createBlock('zefir_dealers/adminhtml_dealer_edit'))
              ->_addLeft($this->getLayout()->createBlock('zefir_dealers/adminhtml_dealer_edit_tabs'));

      $this->renderLayout();
    } else {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zefir_dealers')->__('Dealer does not exist'));
      $this->_redirect('*/*/');
    }
  }
  
  /**
   * Save edit form
   */
  public function saveAction() {
    if ($this->getRequest()->getPost()) {
      try {
        $postData = $this->getRequest()->getPost();
        
        if ($postData['dealer_id'] == '') {
          //empty string won't save new dealer
          $postData['dealer_id'] = null;          
        }
        /**
         * @var Zefir_Dealers_Model_Dealer $dealer
         */
        $dealer = Mage::getModel('zefir_dealers/dealer');
        $dealer->setData($postData)
                ->save();
        
        //save dealer products
        $links = $this->getRequest()->getPost('links');
        if ($links && array_key_exists('products', $links)) {
          $dealer->saveProducts(Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['products']));
        }
        
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zefir_dealers')->__('Dealer was successfully saved'));
        Mage::getSingleton('adminhtml/session')->setDealerData(false);

        $this->_redirect('*/*/');
        return;
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        Mage::getSingleton('adminhtml/session')->setDealerData($this->getRequest()->getPost());
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
        return;
      }
    }
    $this->_redirect('*/*/');
  }
  
  /**
   * Delete dealer
   */
  public function deleteAction() {
    if ($this->getRequest()->getParam('id') > 0) {
      try {
        $dealer = Mage::getModel('zefir_dealers/dealer');
        $dealer->setId($this->getRequest()->getParam('id'))->delete();

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zefir_dealers')->__('Dealer was successfully deleted'));
        $this->_redirect('*/*/');
      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
      }
    }
    $this->_redirect('*/*/');
  }
  
  /**
   * Mass delete
   */
  public function massDeleteAction() {
    $dealerIds = $this->getRequest()->getParam('dealer_id');
    if (!is_array($dealerIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zefir_dealers')->__('Please select dealer(s).'));
    } 
    else {
      $dealer = Mage::getModel('zefir_dealers/dealer');
      foreach ($dealerIds as $dealerId) {
        $dealer->load($dealerId)->delete();
      }
      try {
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zefir_dealers')->__('Total of %d dealer(s) were deleted.', count($dealerIds)));
      } 
      catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
    $this->_redirect('*/*/index');
  }
  
  /**
   * Mass status update
   */
  public function massStatusAction() {
    $dealerIds = $this->getRequest()->getParam('dealer_id');
    if (!is_array($dealerIds)) {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('zefir_dealers')->__('Please select dealer(s).'));
    } 
    else {
      $newStatus = $this->getRequest()->getParam('status') == '1' ? 1 : 0;
      $dealer = Mage::getModel('zefir_dealers/dealer');
      foreach ($dealerIds as $dealerId) {
        $dealer->load($dealerId)->setStatus($newStatus)->save();
      }
      try {
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('zefir_dealers')->__('Total of %d dealer(s) were deleted.', count($dealerIds)));
      } 
      catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
    $this->_redirect('*/*/index');
  }

  /**
   * Ajax grid refresh
   */
  public function gridAction() {
    $this->loadLayout();
    $this->getResponse()->setBody(
            $this->getLayout()->createBlock('zefir_dealers/adminhtml_dealer_grid')->toHtml()
    );
  }
  
  /**
   * Reload grid in dealer edit product tab form
   */
  public function productstabAction()
	{
		$this->loadLayout();
		$this->getLayout()->getBlock('products.grid')
			->setProducts($this->getRequest()->getPost('dealer_products'));
		$this->renderLayout();
	}
  
  public function productsgridAction()
	{
		$this->loadLayout();
		$this->getLayout()->getBlock('products.grid')
			->setProducts($this->getRequest()->getPost('article_products'));
		$this->renderLayout();
	}

}