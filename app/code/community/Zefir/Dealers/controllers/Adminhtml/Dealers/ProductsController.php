<?php

/**
 * Products controller
 *
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Adminhtml_Dealers_ProductsController extends Mage_Adminhtml_Controller_Action {

    /**
     * Reload grid in dealer edit product tab form
     */
    public function dealerstabAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('dealers.grid')
            ->setDealers($this->getRequest()->getPost('dealer_products'));
        $this->renderLayout();
    }

    /**
     * Generate dealer grid for product edit form
     */
    public function dealersgridAction() {
        $this->loadLayout();
        $this->getLayout()->getBlock('dealers.grid')
            ->setDealers($this->getRequest()->getPost('dealer_products'));
        $this->renderLayout();
    }
}
