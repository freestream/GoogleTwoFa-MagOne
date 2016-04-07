<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Anton Samuelsson
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
 * Admin secret hash resource.
 *
 * @package Freestream_TwoFactorAuth
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_TwoFactorAuth_Model_Resource_Admin_Hash
    extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize connection.
     */
    protected function _construct()
    {
        $this->_init('fstwofactorauth/admin_hash', 'hash_id');
    }

    /**
     * Load secret by admin user id.
     *
     * @param  Freestream_TwoFactorAuth_Model_Admin_Hash $hash
     * @param  integer                                   $adminId
     *
     * @return Freestream_TwoFactorAuth_Model_Resource_Admin_Hash
     */
    public function loadByAdminId(Freestream_TwoFactorAuth_Model_Admin_Hash $hash, $adminId)
    {
        $read   = $this->getReadConnection();
        $select = $read->select()->from($this->getMainTable())
            ->where('user_id = :user_id');

        $bind = array(
            'user_id' => $adminId
        );

        $result = $read->fetchRow($select, $bind);

        $hash->setData($result)->setUserId($adminId);

        $this->_afterLoad($hash);

        return $this;
    }

    /**
     * Load secret by admin username.
     *
     * @param  Freestream_TwoFactorAuth_Model_Admin_Hash $hash
     * @param  string                                    $username
     *
     * @return Freestream_TwoFactorAuth_Model_Resource_Admin_Hash
     */
    public function loadByUsername(Freestream_TwoFactorAuth_Model_Admin_Hash $hash, $username)
    {
        $read   = $this->getReadConnection();
        $select = $read->select()->from(array('gtw' => $this->getMainTable()))
            ->join(
                array('au' => $this->getTable('admin/user')),
                'gtw.user_id = au.user_id',
                array()
            )
            ->where('au.username = :username');

        $bind = array(
            'username' => $username
        );

        $result = $read->fetchRow($select, $bind);

        $hash->setData($result);

        $this->_afterLoad($hash);

        return $this;
    }

    /**
     * Checks if all admin users is using One-time password.
     *
     * @return boolean
     */
    public function everyoneUsingOtp()
    {
        $read   = $this->getReadConnection();
        $select = $read->select()->from(
            $this->getMainTable(),
            'mode'
        );

        $result = $read->fetchCol($select);

        if (!is_array($result)) {
            return false;
        }

        $counts = array_count_values($result);

        if (!isset($counts[2])) {
            return false;
        }

        return $counts[2] == count($result);
    }

    /**
     * After load.
     *
     * @param Mage_Core_Model_Abstract $object
     *
     * @return Freestream_TwoFactorAuth_Model_Resource_Admin_Hash
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $object->setHash(Mage::helper('core')->decrypt($object->getHash()));
        }

        return $this;
    }

    /**
     * Before save.
     *
     * @param Mage_Core_Model_Abstract $object
     *
     * @return Freestream_TwoFactorAuth_Model_Resource_Admin_Hash
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $object->setHash(Mage::helper('core')->encrypt($object->getHash()));

        return $this;
    }

    /**
     * After save.
     *
     * @param Mage_Core_Model_Abstract $object
     *
     * @return Freestream_TwoFactorAuth_Model_Resource_Admin_Hash
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $object->setHash(Mage::helper('core')->decrypt($object->getHash()));

        return $this;
    }
}

