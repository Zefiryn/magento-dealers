<?php
/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Dealer_Edit_Tab_Gallery extends Mage_Adminhtml_Block_Widget_Form {

  public function __construct() {
    parent::__construct();
  }

  public function _prepareLayout() {
    parent::_prepareLayout();
    $form = new Varien_Data_Form();
    $form->setHtmlIdPrefix('_gallery');

    //add media grid block
    $grid = Mage::app()->getLayout()->createBlock('zefir_dealers/adminhtml_dealer_edit_tab_gallery_grid','gallery.grid');
    $this->insert($grid, '', false, 'form_after');

    $form->setFieldNameSuffix('gallery');
    $this->setForm($form);
  }

}
