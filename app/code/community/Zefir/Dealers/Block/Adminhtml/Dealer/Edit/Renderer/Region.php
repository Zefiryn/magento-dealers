<?php

/**
 * Dealer address region field renderer
 *
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Block_Adminhtml_Delaer_Edit_Renderer_Region
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface {
    /**
     * Factory instance
     *
     * @var Mage_Core_Model_Abstract
     */
    protected $_factory;

    /**
     * Constructor for Zefir_Dealers_Block_Adminhtml_Delaer_Edit_Renderer_Region class
     *
     * @param array $args
     */
    public function __construct(array $args = array()) {
        $this->_factory = !empty($args['factory']) ? $args['factory'] : Mage::getSingleton('core/factory');
    }

    /**
     * Output the region element and javasctipt that makes it dependent from country element
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element) {
        $country = $element->getForm()->getElement('country_id');
        if(!is_null($country)) {
            $countryId = $country->getValue();
        }
        else {
            return $element->getDefaultHtml();
        }

        $regionId = $element->getForm()->getElement('region_id')->getValue();
        $quoteStoreId = $element->getEntityAttribute()->getStoreId();

        $html = '<tr>';
        $element->setClass('input-text');
        $element->setRequired(true);
        $html .= '<td class="label">' . $element->getLabelHtml() . '</td><td class="value">';
        $html .= $element->getElementHtml();

        $selectName = str_replace('region', 'region_id', $element->getName());
        $selectId = $element->getHtmlId() . '_id';
        $html .= '<select id="' . $selectId . '" name="' . $selectName
            . '" class="select required-entry" style="display:none">';
        $html .= '<option value="">' . $this->_factory->getHelper('zefir_dealers')->__('Please select') . '</option>';
        $html .= '</select>';

        $html .= '<script type="text/javascript">' . "\n";
        $html .= '$("' . $selectId . '").setAttribute("defaultValue", "' . $regionId . '");' . "\n";
        $html .= 'new regionUpdater("' . $country->getHtmlId() . '", "' . $element->getHtmlId() . '", "' .
            $selectId . '", ' . $this->helper('directory')->getRegionJsonByStore($quoteStoreId) . ');' . "\n";
        $html .= '</script>' . "\n";

        $html .= '</td></tr>' . "\n";

        return $html;
    }
}
