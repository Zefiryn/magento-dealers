<?php

/**
 * Dealer-Product Link resource model class
 *
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Model_Resource_Product_Link extends Mage_Core_Model_Resource_Db_Abstract {

    /**
     * Initialize resource model
     */
    protected function _construct() {
        $this->_init('zefir_dealers/product_link', 'link_id');
    }

    /**
     * Save dealer-product association
     *
     * @param Zefir_Dealers_Model_Dealer $dealer
     * @param array                      $links
     * @return Zefir_Dealers_Model_Resource_Product_Link $this
     */
    public function saveDealerProducts($dealer, $links) {

        $adapter = $this->_getWriteAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), array('product_id', 'link_id'))
            ->where('dealer_id = :dealer_id');

        //currently existing products
        $existing = $adapter->fetchPairs($select, array('dealer_id' => $dealer->getId()));

        $current_selection = array_keys($links);
        $existing_selection = array_keys($existing);

        //products removed from dealer
        $deleted = array_diff($existing_selection, $current_selection);
        foreach($deleted as $product_id) {
            if(array_key_exists($product_id, $existing)) {
                $linkId = $existing[$product_id];
                $adapter->delete($this->getMainTable(), array('link_id = ?' => $linkId));
            }

            unset($links[$product_id]); //remove product from links to process
        }

        //new products to add
        $new = array_diff($current_selection, $existing_selection);
        foreach($new as $product_id) {
            $values = array(
                'dealer_id' => $dealer->getId(),
                'product_id' => $product_id,
                'in_stock' => $links[$product_id]['is_in_stock'] != '' ? $links[$product_id]['is_in_stock'] : null
            );
            $link = Mage::getModel('zefir_dealers/product_link')->setData($values)->save();

            unset($link); //free memory; there might be many products here
            unset($links[$product_id]); //remove product from links to process
        }

        //remain products should be only those that already are save and we only need to update stock status
        foreach($links as $product_id => $stock) {
            if(array_key_exists($product_id, $existing)) {
                $update = Mage::getModel('zefir_dealers/product_link')->load($existing[$product_id]);
                $stockStatus = $stock['is_in_stock'] != '' ? $stock['is_in_stock'] : null;
                $update->setInStock($stockStatus)->save();
                unset($update);
            }
        }

        return $this;
    }
}