<?php
/**
 * QR Code image renderer.
 *
 * @package Freestream_TwoFactorAuth
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_TwoFactorAuth_Block_Adminhtml_Permissions_User_Renderer_Qr
    extends Varien_Data_Form_Element_Abstract
{
    /**
     * QR Code element HTML.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $imageUrl = Mage::helper('fstwofactorauth/admin')
            ->getQRCodeGoogleUrl(Mage::registry('permissions_user'));

        return "<img src='{$imageUrl}' />";
    }
}
