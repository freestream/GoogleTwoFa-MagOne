<?php
$installer = $this;
$installer->startSetup();
$connection = $installer->getConnection();

/**
 * Create table 'fsgoogletwofa/admin_hash'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('fsgoogletwofa/admin_hash'))
    ->addColumn(
        'hash_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Role ID'
    )
    ->addColumn(
        'user_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'      => true,
            'nullable'      => false,
        ),
        'User ID'
    )
    ->addColumn(
        'mode',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        1,
        array(
            'unsigned'      => true,
            'nullable'      => false,
        ),
        'Password Mode'
    )
    ->addColumn(
        'hash',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        64,
        array(
            'nullable'  => false,
        ),
        'Hash'
    )
    ->addIndex(
        $installer->getIdxName(
            'fsgoogletwofa/admin_hash',
            array('user_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('user_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->addIndex(
        $installer->getIdxName(
            'fsgoogletwofa/admin_hash',
            array('hash')
        ),
        array('hash')
    )
    ->addForeignKey(
        $installer->getFkName(
            'fsgoogletwofa/admin_hash',
            'user_id',
            'admin/user',
            'user_id'
        ),
        'user_id',
        $installer->getTable('admin/user'),
        'user_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Admin Google Two-Factor Authentication Hash Table');

if (!$installer->getConnection()->isTableExists($table->getName())) {
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();
