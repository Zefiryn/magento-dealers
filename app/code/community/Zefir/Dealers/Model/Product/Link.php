<?php

/**
 * Dealer-Product Link model class
 *
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Model_Product_Link extends Mage_Core_Model_Abstract {

    /**
     * Event prefix name used in magento object related events
     *
     * @var string
     */
    protected $_eventPrefix = 'dealer_product';

    /**
     * Event argument name
     *
     * @var string
     */
    protected $_eventObject = 'product_link';


    /**
     * Constructor function
     */
    public function _construct() {
        $this->_init('zefir_dealers/product_link');
        parent::_construct();
    }

    /**
     * Get collection of all linked products by dealer id
     *
     * @param integer $dealerId
     * @return Zefir_Dealers_Resource_Product_LinkCollection
     */
    public function getProductsForDealer($dealerId) {
        return $this->getCollection()->addFieldToFilter('dealer_id', array('eq' => $dealerId));
    }

    /**
     * Get collection of all linked products by dealer id
     *
     * @param integer $productId
     * @return Zefir_Dealers_Resource_Product_LinkCollection
     */
    public function getDealersForProduct($productId) {
        return $this->getCollection()->addFieldToFilter('product_id', array('eq' => $productId));
    }

    /**
     * Links should be a serialized by grid serializer array with the following structure
     * array(
     *    [product_id] => array( [is_in_stock] => 1|0|null ),
     *    ...
     * )
     *
     * @param Zefir_Dealers_Model_Dealer $dealer
     * @return Zefir_Dealers_Model_Product_Link $this
     */
    public function saveDealerProducts(Zefir_Dealers_Model_Dealer $dealer) {
        $links = $dealer->getLinks();

        if(null !== $links) {
            $links = Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['products']);
            $this->getResource()->saveDealerProducts($dealer, $links);
        }

        return $this;

    }
}
