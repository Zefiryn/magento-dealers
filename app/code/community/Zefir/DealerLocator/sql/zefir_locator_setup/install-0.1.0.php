<?php
/**
 * @var Zefir_DealerLocator_Model_Resource_Setup $installer
 */
$installer = $this;
$installer->startSetup();
$table = $installer->getTable('zefir_dealers/dealer');
$installer->getConnection()->addColumn($table, 'lat', array('type' => Varien_Db_Ddl_Table::TYPE_FLOAT, 'signed' => true, 'nullable' => true, 'comment' => 'Latitude'));
$installer->getConnection()->addColumn($table, 'lng', array('type' => Varien_Db_Ddl_Table::TYPE_FLOAT, 'signed' => true, 'nullable' => true, 'comment' => 'Longitude'));

$installer->endSetup();