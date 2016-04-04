<?php
/**
 * Google Two Factor Authentication Password Modes.
 *
 * @package Freestream_GoogleTwoFa
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_GoogleTwoFa_Model_System_Source_Mode
{
    /**
     * Password only mode.
     *
     * @var integer
     */
    const MODE_PWD_ONLY     = 1;

    /**
     * Password and One-time password mode.
     *
     * @var integer
     */
    const MODE_PWD_AND_OTP  = 2;

    /**
     * Option array.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => self::MODE_PWD_ONLY,
                'label' => Mage::helper('fsgoogletwofa')->__('Password Only')
            ),
            array(
                'value' => self::MODE_PWD_AND_OTP,
                'label' => Mage::helper('fsgoogletwofa')->__('Password and One-time Password')
            ),
        );
    }

    /**
     * Option value hash.
     *
     * @return array
     */
    public function toOptionHash()
    {
        $optionArray    = $this->toOptionArray();
        $options        = array();

        foreach ($optionArray as $option) {
            $options[$option['value']] = $option['label'];
        }

        return $options;
    }
}
