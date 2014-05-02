<?php
/**
 * Base dealer resource model collection class
 * 
 * @author  Zefiryn
 * @package Zefir_Dealers
 * @license http://www.mozilla.org/MPL/2.0/  Mozilla Public License 2.0 (MPL)
 */
class Zefir_Dealers_Model_Resource_Dealer_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

  /**
   * Define resource model
   *
   */
  protected function _construct() {
    $this->_init('zefir_dealers/dealer');    
    parent::_construct();
  }

  /**
   * Join table to collection select
   *
   * @param string $table
   * @param string $cond
   * @param string $cols
   * @return Mage_Core_Model_Resource_Db_Collection_Abstract
   */
  public function joinLeft($table, $cond, $cols = '*')
  {
    if (is_array($table)) {
      foreach ($table as $k => $v) {
        $alias = $k;
        $table = $v;
        break;
      }
    } else {
      $alias = $table;
    }

    if (!isset($this->_joinedTables[$table])) {
      $this->getSelect()->joinLeft(
        array($alias => $this->getTable($table)),
        $cond,
        $cols
      );
      $this->_joinedTables[$alias] = true;
    }
    return $this;
  }
  
}