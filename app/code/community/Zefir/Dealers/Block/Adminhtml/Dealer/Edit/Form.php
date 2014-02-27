<?php
/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */

class Zefir_Dealers_Block_Adminhtml_Dealer_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

  protected function _prepareForm() {
    $form = new Varien_Data_Form(array(
        'id' => 'edit_form',
        'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
        'method' => 'post',
            )
    );

    $form->setUseContainer(true);
    $this->setForm($form);
    return parent::_prepareForm();
  }

}