<?php

/**
 * Observer class
 *
 * @author  Zefiryn
 * @package Zefir_DealerLocator
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_DealerLocator_Model_Observer {

    /**
     * Add longitude and latitude fields to the dealer form
     *
     * @param Varien_Event_Observer $observer
     * @return \Zefir_DealerLocator_Model_Observer
     */
    public function addFieldsToDealerForm(Varien_Event_Observer $observer) {

        $form = $observer->getEvent()->getForm();
        $addresFieldset = $form->getElement('address_form');
        $addresFieldset->addField('lat', 'text', array(
            'label' => Mage::helper('zefir_dealers')->__('Latitude'),
            'class' => '',
            'required' => false,
            'name' => 'lat',
            'after_element_html' => '<p class="note"><span>' . Mage::helper('zefir_dealers')->__('You can provide geo cooridnates here. If this is empty they will be calculated automatically.') . '</span></p>',
        ));
        $addresFieldset->addField('lng', 'text', array(
            'label' => Mage::helper('zefir_dealers')->__('Longitude'),
            'class' => '',
            'required' => false,
            'name' => 'lng',
            'after_element_html' => '<p class="note"><span>' . Mage::helper('zefir_dealers')->__('You can provide geo cooridnates here. If this is empty they will be calculated automatically.') . '</span></p>',
        ));
        $addresFieldset->addField('auto_coordinates', 'checkbox', array(
            'name' => 'auto_coordinates',
            'label' => Mage::helper('zefir_dealers')->__('Recalculate coordinates'),
            'value' => 1,
            'after_element_html' => '<p class="note"><span>' . Mage::helper('zefir_dealers')->__('Check this box if you want to recalculate coordinates') . '</span></p>',
        ));

        return $this;

    }

    /**
     * Set dealer lat and lng fields before saving
     *
     * @param Varien_Event_Observer $observer
     * @return \Zefir_DealerLocator_Model_Observer
     */
    public function localizeDealer(Varien_Event_Observer $observer) {
        if(Mage::helper('zefir_locator')->isLocalizatorEnabled()) {
            $dealer = $observer->getEvent()->getDealer();
            /**
             * @var Zefir_DealerLocator_Helper_Localizator_Abstract $localizator
             */
            $localizator = Mage::helper('zefir_locator')->getLocalizator();
            if($this->_shouldCheckGeoData($dealer)) {
                $coords = $localizator->getCoordinates($dealer->getData());
                if(array_key_exists('lat', $coords) && array_key_exists('lng', $coords)) {
                    $dealer->setLat($coords['lat']);
                    $dealer->setLng($coords['lng']);
                }
            }
        }

        return $this;
    }

    /**
     * Check if we should request webservice for new coordinates
     *
     * @param Zefir_Dealers_Model_Dealer $dealer
     * @return boolean
     */
    protected function _shouldCheckGeoData(Zefir_Dealers_Model_Dealer $dealer) {
        //auto_coordinates checkbox was checked
        $force = $dealer->getAutoCoordinates() || Mage::app()->getRequest()->getPost('auto_coordinates');
        if($force != null) {
            return true;
        }
        else {
            return ($dealer->dataHasChangedFor('street')
                || $dealer->dataHasChangedFor('city')
                || $dealer->dataHasChangedFor('zipcode')
                || $dealer->dataHasChangedFor('region_id')
                || $dealer->dataHasChangedFor('region')
                || $dealer->dataHasChangedFor('country'));
        }
    }

    /**
     * Add recalculate location mass action
     *
     * @param Varien_Event_Observer $observer
     * @return \Zefir_DealerLocator_Model_Observer
     */
    public function addMassactionToGrid(Varien_Event_Observer $observer) {
        if(Mage::helper('zefir_locator')->isLocalizatorEnabled()) {
            $grid = $observer->getEvent()->getGrid();
            $grid->getMassactionBlock()->addItem('refresh_geo_location', array(
                'label' => Mage::helper('tax')->__('Refresh Geo Location'),
                'url' => $grid->getUrl('*/dealerlocation/massRefresh', array('' => ''))
            ));
        }

        return $this;
    }
}