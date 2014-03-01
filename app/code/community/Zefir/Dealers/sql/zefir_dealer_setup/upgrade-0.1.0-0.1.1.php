<?php
/**
 * @var Zefir_Dealers_Model_Resource_Setup $installer
 */
$installer = $this;  
$installer->startSetup();
$table = $installer->getTable('zefir_dealers/dealer');
$installer->getConnection()->addColumn($table, 'street', array('type' => Varien_Db_Ddl_Table::TYPE_TEXT, 'nullable' => true, 'comment' => 'Street', 'length' => 255));
$installer->getConnection()->addColumn($table, 'city', array('type' => Varien_Db_Ddl_Table::TYPE_TEXT, 'nullable' => true, 'comment' => 'City', 'length' => 255));
$installer->getConnection()->addColumn($table, 'region', array('type' => Varien_Db_Ddl_Table::TYPE_TEXT, 'nullable' => true, 'comment' => 'Region', 'length' => 255));
$installer->getConnection()->addColumn($table, 'region_id', array('type' => Varien_Db_Ddl_Table::TYPE_INTEGER, 'nullable' => true, 'comment' => 'Region ID'));
$installer->getConnection()->addColumn($table, 'country_id', array('type' => Varien_Db_Ddl_Table::TYPE_TEXT, 'nullable' => true, 'comment' => 'Country', 'length' => 5));
$installer->getConnection()->addColumn($table, 'zipcode', array('type' => Varien_Db_Ddl_Table::TYPE_TEXT, 'nullable' => true, 'comment' => 'Zip Code', 'length' => 12));
        
$installer->endSetup();$installer->endSetup();