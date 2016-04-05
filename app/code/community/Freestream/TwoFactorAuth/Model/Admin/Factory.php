<?php
/**
 * Google Two-Factor Authentication.
 *
 * @package Freestream_TwoFactorAuth
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
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

