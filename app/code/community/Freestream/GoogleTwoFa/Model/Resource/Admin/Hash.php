<?php
/**
 * Admin secret hash resource.
 *
 * @package Freestream_GoogleTwoFa
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_GoogleTwoFa_Model_Resource_Admin_Hash
    extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize connection.
     */
    protected function _construct()
    {
        $this->_init('fsgoogletwofa/admin_hash', 'hash_id');
    }

    /**
     * Load secret by admin user id.
     *
     * @param  Freestream_GoogleTwoFa_Model_Admin_Hash $hash
     * @param  integer                                 $adminId
     *
     * @return Freestream_GoogleTwoFa_Model_Resource_Admin_Hash
     */
    public function loadByAdminId(Freestream_GoogleTwoFa_Model_Admin_Hash $hash, $adminId)
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
     * @param  Freestream_GoogleTwoFa_Model_Admin_Hash $hash
     * @param  string                                  $username
     *
     * @return Freestream_GoogleTwoFa_Model_Resource_Admin_Hash
     */
    public function loadByUsername(Freestream_GoogleTwoFa_Model_Admin_Hash $hash, $username)
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
     * @return Freestream_GoogleTwoFa_Model_Resource_Admin_Hash
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $object->setHash(Mage::helper('core')->decrypt($object->getHash()));

        return $this;
    }

    /**
     * Before save.
     *
     * @param Mage_Core_Model_Abstract $object
     *
     * @return Freestream_GoogleTwoFa_Model_Resource_Admin_Hash
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $object->setHash(Mage::helper('core')->encrypt($object->getHash()));

        return $this;
    }
}

