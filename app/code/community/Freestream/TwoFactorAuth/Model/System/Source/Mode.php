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
 * Two Factor Authentication Password Modes.
 *
 * @author Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_TwoFactorAuth_Model_System_Source_Mode
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
                'label' => Mage::helper('fstwofactorauth')->__('Password Only')
            ),
            array(
                'value' => self::MODE_PWD_AND_OTP,
                'label' => Mage::helper('fstwofactorauth')->__('Password and One-time Password')
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

