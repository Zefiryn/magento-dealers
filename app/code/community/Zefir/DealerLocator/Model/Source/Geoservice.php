<?php
/**
 * Available Geoservices source class
 * 
 * @author  Zefiryn
 * @package Zefir_DealerLocator
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_DealerLocator_Model_Source_Geoservice extends Varien_Object {

  public function toOptionArray() {        
    $options = array();
    foreach(Mage::getConfig()->getNode('geoservices/children')->children() as $node) {
      $translation = array();
      
      //prepare translation array;
      if ((bool)$node->getAttribute('translate')) {        
        $helper = $node->getAttribute('module') ? Mage::helper((string)$node->getAttribute('module')) : Mage::helper('core');
        foreach(explode(' ', (string)$node->getAttribute('translate')) as $elem) {
          $translation[$elem] = $helper->__((string)$node->$elem);
        }      
      }
      else {
        $translation['label'] = (string)$node->getAttribute('label');
      }
      $options[] = array('value' => $node->getName(), 'label' => $translation['label']);
    }
    
    return $options;
  }
  
}