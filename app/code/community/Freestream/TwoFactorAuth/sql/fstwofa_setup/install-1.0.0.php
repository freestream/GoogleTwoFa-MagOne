6<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2018 Anton Samuelsson
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
?>
<?php
/**
 * Initial table structure for admin hash.
 *
 * @author Anton Samuelsson <samuelsson.anton@gmail.com>
 */
?>
<?php
$installer = $this;
$installer->startSetup();
$connection = $installer->getConnection();

/**
 * Create table 'fstwofa/admin_hash'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('fstwofa/admin_hash'))
    ->addColumn(
        'hash_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        [
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ],
        'Hash ID'
    )
    ->addColumn(
        'user_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        [
            'unsigned'      => true,
            'nullable'      => false,
        ],
        'User ID'
    )
    ->addColumn(
        'mode',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        1,
        [
            'unsigned'      => true,
            'nullable'      => false,
        ],
        'Password Mode'
    )
    ->addColumn(
        'hash',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        64,
        [
            'nullable'  => false,
        ],
        'Hash'
    )
    ->addIndex(
        $installer->getIdxName(
            'fstwofa/admin_hash',
            ['user_id'],
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        ['user_id'],
        ['type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE]
    )
    ->addIndex(
        $installer->getIdxName(
            'fstwofa/admin_hash',
            ['hash']
        ),
        ['hash']
    )
    ->addForeignKey(
        $installer->getFkName(
            'fstwofa/admin_hash',
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
    ->setComment('Admin Two-Factor Authentication Hash Table');

if (!$installer->getConnection()->isTableExists($table->getName())) {
    $installer->getConnection()->createTable($table);
}

$installer->endSetup();

