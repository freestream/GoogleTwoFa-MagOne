<?php
/**
 * QR Code image renderer.
 *
 * @package Freestream_GoogleTwoFa
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_GoogleTwoFa_Block_Adminhtml_Permissions_User_Renderer_Qr
    extends Varien_Data_Form_Element_Abstract
{
    /**
     * QR Code element HTML.
     *
     * @return string
     */
    public function getElementHtml()
    {
        $imageUrl = Mage::helper('fsgoogletwofa')
            ->getQRCodeGoogleUrl(Mage::registry('permissions_user'));

        return "<img src='{$imageUrl}' />";
    }
}
