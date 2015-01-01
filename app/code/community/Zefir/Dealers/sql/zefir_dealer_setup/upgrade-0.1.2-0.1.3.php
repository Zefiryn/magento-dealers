<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()->dropTable($installer->getTable('zefir_dealers/gallery'));
$table = $installer->getConnection()->newTable($installer->getTable('zefir_dealers/gallery'))
    ->addColumn('image_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true, 'nullable' => false, 'primary' => true, 'identity' => true), 'Image ID')
    ->addColumn('dealer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array('unsigned' => true, 'nullable' => false, 'primary' => true), 'Dealer ID')
    ->addColumn('file', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array('nullable' => false), 'File Path')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array('nullable' => true), 'Title')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 800, array('nullable' => true), 'Short Description')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, 6, array('nullable' => true), 'Short Description')
    ->addColumn('disabled', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array('nullable' => true), 'Excluded')
    ->addIndex($installer->getIdxName($installer->getTable('zefir_dealers/gallery'), array('dealer_id'), Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX), array('dealer_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX));
$installer->getConnection()->createTable($table);


$installer->endSetup();