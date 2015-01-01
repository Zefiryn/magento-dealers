<?php
/**
 * @var Zefir_Dealers_Model_Resource_Setup $installer
 */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->dropTable($installer->getTable('zefir_dealers/dealer'));
$table = $installer->getConnection()->newTable($installer->getTable('zefir_dealers/dealer'))
    ->addColumn('dealer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true, 'nullable' => false, 'primary' => true, 'identity' => true), 'Dealer ID')
    ->addColumn('dealer_code', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array('nullable' => false), 'Dealer Code')
    ->addColumn('dealer_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array('nullable' => false), 'Dealer Name')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array('nullable' => false, 'default' => '1'), 'Status');
$installer->getConnection()->createTable($table);

$installer->endSetup();