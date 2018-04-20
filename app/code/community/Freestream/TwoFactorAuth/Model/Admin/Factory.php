<?php
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
 * Two-Factor Authentication.
 *
 * @author Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_TwoFactorAuth_Model_Admin_Factory
    extends Freestream_TwoFactorAuth_Model_Factory_Abstract
{
    /**
     * Authenticate one-time password for username.
     *
     * @param  string $username
     * @param  string $otp
     *
     * @throws Mage_Core_Exception
     *
     * @return boolean
     */
    public function authenticateOtp($username, $otp)
    {
        $secretModel = Mage::getModel('fstwofactorauth/admin_hash')
            ->loadByUsername($username);

        if (!$secretModel->getId() || $secretModel->getMode() == 1) {
            return true;
        }

        if (!$this->verifyKey($secretModel->getHash(), $otp)) {
            Mage::throwException(Mage::helper('adminhtml')->__('Access denied.'));
        }

        return true;
    }
}

