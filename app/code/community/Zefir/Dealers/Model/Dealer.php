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
   * @var Zefir_Dealers_Model_Product_Link
   */
  protected $_linkInstance;

  /**
   * @var Zefir_Dealers_Model_Gallery
   */
  protected $_galleryInstance;

  /**
   * Constructor function
   *
   * @return void
   */
  public function _construct() {    
    $this->_init('zefir_dealers/dealer');
    parent::_construct();
  }

  /**
   * Process and save dependent data
   *
   * @return Zefir_Dealers_Model_Dealer
   */
  protected function _afterSave() {

    //save dealer gallery
    $this->_saveGallery();

    //save dealer products
    $this->_saveProducts();
    return $this;

  }

  /**
   * Save dealer products
   *
   * @return Zefir_Dealers_Model_Dealer
   */
  protected function _saveProducts() {
    $this->getProductLinkInstance()->saveDealerProducts($this);
    return $this;
  }

  /**
   * Process saving dealer images
   *
   * @return Zefir_Dealers_Model_Dealer
   */
  protected function _saveGallery() {
    $this->getGalleryInstance()->saveDealerGallery($this);
    return $this;
  }


  /**
   * Get instance of product link model
   *
   * @return false|Mage_Core_Model_Abstract|Zefir_Dealers_Model_Product_Link
   */
  public function getProductLinkInstance() {
    if (null === $this->_linkInstance) {
      $this->_linkInstance = Mage::getModel('zefir_dealers/product_link');
    }
    return $this->_linkInstance;
  }

  /**
   * Get instance of gallery model
   *
   * @return false|Mage_Core_Model_Abstract|Zefir_Dealers_Model_Gallery
   */
  public function getGalleryInstance() {
    if (null === $this->_galleryInstance) {
      $this->_galleryInstance = Mage::getModel('zefir_dealers/gallery');
    }
    return $this->_galleryInstance;
  }


}
