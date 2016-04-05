<?php
/**
 * Admin secret hash model.
 *
 * @package Freestream_TwoFactorAuth
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_TwoFactorAuth_Model_Admin_Hash
    extends Mage_Core_Model_Abstract
{
    /**
     * Initialize connection.
     */
    protected function _construct()
    {
        $this->_init('fstwofactorauth/admin_hash');
    }

    /**
     * Load by admin model.
     *
     * @param  Mage_Admin_Model_User $admin
     *
     * @return array|boolean
     */
    public function loadByAdmin(Mage_Admin_Model_User $admin)
    {
        $this->_getResource()->loadByAdminId($this, $admin->getId());

        return $this;
    }

    /**
     * Load by admin ID.
     *
     * @param  integer $adminId
     *
     * @return array|boolean
     */
    public function loadByAdminId($adminId)
    {
        $this->_getResource()->loadByAdminId($this, $adminId);

        return $this;
    }


    /**
     * Load by admin username.
     *
     * @param  string $username
     *
     * @return array|boolean
     */
    public function loadByUsername($username)
    {
        $this->_getResource()->loadByUsername($this, $username);

        return $this;
    }
}

