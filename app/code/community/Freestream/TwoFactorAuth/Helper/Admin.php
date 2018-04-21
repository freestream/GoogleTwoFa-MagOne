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
 * Admin user helper class.
 *
 * @author Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_TwoFactorAuth_Helper_Admin
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
        $secretModel = Mage::getSingleton('fstwofa/admin_hash')
            ->loadByAdmin($admin);

        $secret     = $secretModel->getHash();
        $username   = Mage::helper('fstwofa')->getCleanString($admin->getUsername());
        $company    = Mage::helper('fstwofa')->getCleanString(
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
        return Mage::getResourceModel('fstwofa/admin_hash')->everyoneUsingOtp();
    }
}

