<?php
/**
 * @var Zefir_Dealers_Model_Resource_Setup $installer
 */
$installer = $this;  
$installer->startSetup();

$installer->getConnection()->dropTable($installer->getTable('zefir_dealers/product_link'));
$table = $installer->getConnection()->newTable($installer->getTable('zefir_dealers/product_link'))
                  ->addColumn('link_id',Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true,'nullable' => false,'primary' => true,'identity' => true ), 'Link ID')
                  ->addColumn('dealer_id',Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true,'nullable' => false), 'Dealer ID')
                  ->addColumn('product_id',Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true,'nullable' => false), 'Product ID')
                  ->addColumn('in_stock',Varien_Db_Ddl_Table::TYPE_VARCHAR, 1, array('nullable'  => true), 'In Stock Status')
                  ->addIndex($installer->getIdxName($installer->getTable('zefir_dealers/product_link'), array('dealer_id', 'product_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), array('dealer_id', 'product_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
                  ->addIndex($installer->getIdxName($installer->getTable('zefir_dealers/product_link'), array('dealer_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), array('dealer_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX))
                  ->addIndex($installer->getIdxName($installer->getTable('zefir_dealers/product_link'), array('product_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE), array('product_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX));
$installer->getConnection()->createTable($table);

$installer->endSetup();