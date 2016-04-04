<?php
/**
 * Helper class.
 *
 * @package Freestream_GoogleTwoFa
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_GoogleTwoFa_Helper_Admin
{
    /**
     * Generates a QR code image URL.
     *
     * @param  Mage_Admin_Model_User $admin
     *
     * @return string
     */
    public function getQRCodeGoogleUrl(Mage_Admin_Model_User $admin)
    {
        $secretModel = Mage::getSingleton('fsgoogletwofa/admin_hash')
            ->loadByAdmin($admin);

        $secret     = $secretModel->getHash();
        $username   = Mage::helper('fsgoogletwofa')->getCleanString($admin->getUsername());
        $company    = Mage::helper('fsgoogletwofa')->getCleanString(
            Mage::getStoreConfig('general/store_information/name')
        );
        $name       = "{$company}:{$admin->getUsername()}";
        $urlencoded = urlencode("otpauth://totp/{$name}?secret={$secret}&issuer={$company}");

        return "https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl={$urlencoded}";
    }

    /**
     * Checks if all admin users is using One-time password.
     *
     * @return boolean
     */
    public function everyoneUsingOtp()
    {
        return Mage::getResourceModel('fsgoogletwofa/admin_hash')->everyoneUsingOtp();
    }
}
