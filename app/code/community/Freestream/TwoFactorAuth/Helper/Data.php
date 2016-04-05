<?php
/**
 * Helper class.
 *
 * @package Freestream_TwoFactorAuth
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_TwoFactorAuth_Helper_Data
    extends Mage_Core_Helper_Abstract
{
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
}
