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
   *
   * @return void
   */
  public function _construct() {    
    $this->_init('zefir_dealers/dealer');
    parent::_construct();
  }
  
  /**
   * Get dealer ID
   * 
   * @return integer
   */
  public function getId() {
    return $this->getDealerId();    
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
   * Links should be a serialized by grid serializer array with the following structure
   * array(
   *    [product_id] => array( [is_in_stock] => 1|0|null ),
   *    ...
   * )
   *
   * @todo add actual saving procedure
   * @return Zefir_Dealers_Model_Dealer
   */
  protected function _saveProducts() {
//    $links = $this->getLinks();
//    $links = Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['products']);
//    foreach($links as $product_id => $stock) {
//      $values = array(
//                    'dealer_id' => $this->getId(),
//                    'product_id' => $product_id,
//                    'in_stock' => $stock['is_in_stock'] != '' ? $stock['is_in_stock'] : null
//                  );
//      $link = Mage::getModel('zefir_dealers/product_link')->setData($values)->save();
//      unset($link); //free memory; there might be many products here
//    }
    
    return $this;
  }

  /**
   * Process saving dealer images
   *
   * @return Zefir_Dealers_Model_Dealer
   */
  protected function _saveGallery() {

    $galleryPost = $this->getGallery();
    if (array_key_exists('block_id', $galleryPost)) {
      $prefix = $galleryPost['block_id'];
      $images = $this->getData($prefix . '_save');
      $data = json_decode($images['images'], true);
      foreach ($data as $image) {
        $imageObject = Mage::getModel('zefir_dealers/gallery');
        if ($image['removed']) {
          $imageObject->removeImage($image);
          continue;
        } elseif (!array_key_exists('image_id', $image)) {
          $image['file'] = $imageObject->copyImage($image);
        }
        try {
          $image['dealer_id'] = $this->getId();
          $imageObject->setData($image);
          $imageObject->save();
        }
        catch (Exception $e) {
          Mage::logException($e);
        }
      }
    }

    return $this;
  }

}
