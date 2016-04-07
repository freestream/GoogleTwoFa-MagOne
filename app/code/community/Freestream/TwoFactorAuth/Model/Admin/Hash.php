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

