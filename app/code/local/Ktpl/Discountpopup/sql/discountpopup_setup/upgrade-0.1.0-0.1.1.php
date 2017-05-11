<?php
 
$installer = $this;
$connection = $installer->getConnection();
 
$installer->startSetup();
 
$installer->getConnection()
    ->addColumn($installer->getTable('salesrule/rule'),
    'use_in_popup',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
        'nullable' => true,
        'default' => 0,
        'comment' => 'Use In Popup'
    )
);
 
$installer->endSetup();