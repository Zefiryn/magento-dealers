<?php
/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Dealer_Edit_Tab_Gallery_Grid extends Mage_Adminhtml_Block_Widget {

  /**
   * @var Zefir_Dealers_Model_Resource_Gallery_Collection
   */
  protected $_imagesCollection;

  public function __construct() {
    parent::__construct();
    $this->setTemplate('zefir/dealers/tab/gallery_grid.phtml');
  }

  protected function _prepareLayout() {
    $this->setChild('uploader', $this->getLayout()->createBlock('adminhtml/media_uploader'));

    //set flash uploader
    $this->getUploader()->getConfig()
      ->setUrl(Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*/*/upload'))
      ->setFileField('image')
      ->setFilters(array(
        'images' => array(
          'label' => Mage::helper('adminhtml')->__('Images (.gif, .jpg, .png)'),
          'files' => array('*.gif', '*.jpg', '*.jpeg', '*.png')
        )
      ));

    return parent::_prepareLayout();
  }

  /**
   * Retrieve images for the current dealer
   *
   * @return Zefir_Dealers_Model_Resource_Gallery_Collection
   */
  public function getImageCollection() {
    if (null === $this->_imagesCollection) {
      $this->_imagesCollection = Mage::getModel('zefir_dealers/gallery')
        ->getCollection()
        ->addFieldToFilter('dealer_id', array('eq' => $this->getDealer()->getId()))
        ->setOrder('position', Varien_Data_Collection::SORT_ORDER_ASC);
    }

    return $this->_imagesCollection;
  }

  /**
   * Retrive uploader block
   *
   * @return Mage_Adminhtml_Block_Media_Uploader
   */
  public function getUploader() {
    return $this->getChild('uploader');
  }

  /**
   * Retrive uploader block html
   *
   * @return string
   */
  public function getUploaderHtml() {
    return $this->getChildHtml('uploader');
  }

  public function getJsObjectName() {
    return $this->getHtmlId() . 'JsObject';
  }

  public function getAddImagesButton() {
    return $this->getButtonHtml(
      Mage::helper('catalog')->__('Add New Images'), $this->getJsObjectName() . '.showUploader()', 'add', $this->getHtmlId() . '_add_images_button'
    );
  }

  public function getImagesJson() {
    if ($this->getImageCollection()->count() > 0) {
      $images = array();
      foreach($this->getImageCollection() as $image) {
        /**
         * @var Zefir_Dealers_Model_Resource_Gallery @image
         */
        $images[] = array(
          "image_id" => $image->getId(),
          "url" => $image->getUrlPath(),
          "file" => $image->getFile(),
          "title" => $image->getTitle(),
          "description" => $image->getDescription(),
          "position" => $image->getPosition(),
          "disabled" => $image->getDisabled(),
          "removed" => 0
        );
      }
      return json_encode($images);
    }
    return '[]';
  }

  public function getImagesValuesJson() {
    $values = array();
    return Mage::helper('core')->jsonEncode($values);
  }

  public function getDealer() {
    return Mage::registry('dealer_data');
  }

}
