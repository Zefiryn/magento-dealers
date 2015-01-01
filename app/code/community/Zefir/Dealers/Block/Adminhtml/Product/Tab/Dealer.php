<?php

/**
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Product_Tab_Dealer
    extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    /**
     * @var
     */
    protected $_selectedDealers;

    protected $_dealerCollection;

    public function __construct() {
        parent::__construct();
        $this->setId('dealers_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('entity_id');
        $this->setDefaultFilter(array('in_dealers' => 1));
        $this->setSaveParametersInSession(false);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('zefir_dealers/dealer')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('in_dealers', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'field_name' => 'in_dealers',
            'name' => 'in_dealers',
            'values' => $this->_getDelersSelection(),
            'align' => 'center',
            'index' => 'dealer_id'
        ));

        $this->addColumn('dealer_id', array(
            'header' => Mage::helper('zefir_dealers')->__('ID'),
            'width' => '50px',
            'type' => 'number',
            'index' => 'dealer_id'
        ));

        $this->addColumn('dealer_name', array(
            'header' => Mage::helper('zefir_dealers')->__('Dealer Name'),
            'index' => 'dealer_name'
        ));

        $this->addColumn('dealer_code', array(
            'header' => Mage::helper('zefir_dealers')->__('Dealer Code'),
            'index' => 'dealer_code'
        ));

        $stockOptions = array(null => '');
        foreach(Mage::getSingleton('cataloginventory/source_stock')->toOptionArray() as $option) {
            $stockOptions[$option['value']] = $option['label'];
        }

        $this->addColumn('is_in_stock', array(
            'header' => Mage::helper('cataloginventory')->__('Stock'),
            'width' => '90px',
            'index' => 'is_in_stock',
            'type' => 'select',
            'options' => $stockOptions,
            'editable' => true,
            'edit_only' => true
        ));


        return parent::_prepareColumns();
    }

    /**
     * Create url for grid regenerate
     *
     * @return mixed|string
     */
    public function getGridUrl() {
        if($this->_getData('grid_url')) {
            return $this->_getData('grid_url');
        }

        return $this->getUrl('*/dealers_products/dealersgrid', array('_current' => true, 'current_product' => $this->_getProductId()));
    }

    /**
     * Return current dealers selection
     *
     * @return array
     */
    protected function _getDelersSelection() {   // Used in grid to return selected customers values.
        $dealers = $this->getDealers();
        if(!is_array($dealers)) {
            $dealers = array_keys($this->getSelectedDealers());
        }

        return $dealers;
    }

    /**
     * Prepare array with selected dealers
     *
     * @return array
     */
    public function getSelectedDealers() {

        if(null === $this->_selectedDealers) {
            $product_id = $this->getRequest()->getParam('current_product');
            $dealerProducts = Mage::getModel('zefir_dealers/product_link')->getDealersForProduct($product_id);
            $dealers = array();
            foreach($dealerProducts as $link) {
                $dealers[$link->getDealerId()] = array('is_in_stock' => $link->getInStock());
            }
            $this->_selectedDealers = $dealers;
        }

        return $this->_selectedDealers;
    }

    /**
     * Add column filters
     *
     * @param Varien_Object $column
     * @return Zefir_Dealers_Block_Adminhtml_Product_Tab_Dealer $this
     */
    protected function _addColumnFilterToCollection($column) {
        // Set custom filter for in dealers flag
        if($column->getId() == 'in_dealers') {
            $dealersIds = $this->_getDelersSelection();
            if(empty($dealersIds)) {
                $dealersIds = 0;
            }
            if($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('main_table.dealer_id', array('in' => $dealersIds));
            }
            elseif(!empty($dealersIds)) {
                $this->getCollection()->addFieldToFilter('main_table.dealer_id', array('nin' => $dealersIds));
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * Retrieve current product id
     *
     * @return mixed
     */
    protected function _getProductId() {
        return Mage::app()->getRequest()->getParam('current_product');
    }


    /**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel() {
        return $this->__('Dealers');
    }

    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle() {
        return $this->__('Click here to view product dealers');
    }

    /**
     * Show tab only for simple products
     *
     * @return bool
     */
    public function canShowTab() {
        return Mage::registry('current_product')->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE;
    }

    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden() {
        return false;
    }

    /**
     * Retrieve the class name of the tab
     * Return 'ajax' here if you want the tab to be loaded via Ajax
     *
     * return string
     */
    public function getTabClass() {
        return 'ajax dealers-tab';
    }

    /**
     * Determine whether to generate content on load or via AJAX
     * If true, the tab's content won't be loaded until the tab is clicked
     * You will need to setup a controller to handle the tab request
     *
     * @return bool
     */
    public function getSkipGenerateContent() {
        return true;
    }

    /**
     * Retrieve the URL used to load the tab content
     * Return the URL here used to load the content by Ajax
     * see self::getSkipGenerateContent & self::getTabClass
     *
     * @return string
     */
    public function getTabUrl() {
        return $this->getUrl('*/dealers_products/dealerstab', array('_current' => false, 'current_product' => Mage::registry('current_product')->getId()));
    }
}