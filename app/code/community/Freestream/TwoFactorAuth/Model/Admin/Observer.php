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
class Freestream_TwoFactorAuth_Model_Admin_Observer
{
    /**
     * Login authentication.
     *
     * @param  Varien_Event_Observer $observer
     *
     * @return Freestream_TwoFactorAuth_Model_Admin_Observer
     */
    public function authenticateOtp(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfigFlag('fstwofa/general/enabled')) {
            return $this;
        }

        $username   = $observer->getUsername();
        $post       = new Varien_Object(Mage::app()->getRequest()->getPost());

        Mage::getModel('fstwofa/admin_factory')
            ->authenticateOtp($username, $post->getData('login/twofactorauth_otp'));

        return $this;
    }

    /**
     * Assign a secret key to admin user.
     *
     * @param  Varien_Event_Observer $observer
     *
     * @return Freestream_TwoFactorAuth_Model_Observer
     */
    public function assignSecretToAdmin(Varien_Event_Observer $observer)
    {
        $user   = $observer->getObject();
        $model  = Mage::getModel('fstwofa/admin_hash')
            ->loadByAdmin($user);

        if (!$model->getId()) {
            $secret = Mage::getModel('fstwofa/admin_factory')->generateSecretKey();
            $model->setHash($secret)->setMode(1)->save();
        }

        return $this;
    }

    /**
     * Save auth mode for admin user.
     *
     * @param  Varien_Event_Observer $observer
     *
     * @return Freestream_TwoFactorAuth_Model_Observer
     */
    public function saveAdminSecret(Varien_Event_Observer $observer)
    {
        $post   = new Varien_Object(Mage::app()->getRequest()->getPost());
        $model  = Mage::getModel('fstwofa/admin_hash')
            ->loadByAdminId($post->getData('user_id'));

        $model->setMode($post->getData('twofactorauth/mode'));

        if (!$model->getHash()) {
            $model->setHash(
                Mage::getModel('fstwofa/admin_factory')->generateSecretKey()
            );
        }

        $model->save();

        return $this;
    }
}

