<?php
/**
 * Helper class.
 *
 * @package Freestream_GoogleTwoFa
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_GoogleTwoFa_Helper_Data
    extends Mage_Core_Helper_Abstract
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
        $company    = Mage::getStoreConfig('general/store_information/name');
        $name       = "{$company}:{$admin->getUsername()}";
        $urlencoded = urlencode("otpauth://totp/{$name}?secret={$secret}&issuer={$company}");

        return "https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl={$urlencoded}";
    }

    /**
     * Returns a string with valid characters.
     *
     * @param  Mage_Admin_Model_User $admin
     *
     * @return string
     */
    public function getCleanString($string)
    {
        if (strpos($string = htmlentities($string, ENT_QUOTES, 'UTF-8'), '&') !== false) {
            $string = html_entity_decode(
                preg_replace(
                    '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i',
                    '$1',
                    $string
                ),
                ENT_QUOTES,
                'UTF-8'
            );
        }

        return preg_replace('/\s+/', '', ucwords($string));
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
