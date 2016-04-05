<?php
/**
 * Google Two-Factor Authentication.
 *
 * @package Freestream_TwoFactorAuth
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
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
        $username   = $observer->getUsername();
        $post       = new Varien_Object(Mage::app()->getRequest()->getPost());

        Mage::getModel('fstwofactorauth/admin_factory')
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
        $model  = Mage::getModel('fstwofactorauth/admin_hash')
            ->loadByAdmin($user);

        if (!$model->getId()) {
            $secret = Mage::getModel('fstwofactorauth/admin_factory')->generateSecretKey();
            $model->setHash($secret)->setMode(1)->save();
        }

        return $this;
    }

    /**
     * Save password mode for admin user.
     *
     * @param  Varien_Event_Observer $observer
     *
     * @return Freestream_TwoFactorAuth_Model_Observer
     */
    public function saveAdminSecret(Varien_Event_Observer $observer)
    {
        $post   = new Varien_Object(Mage::app()->getRequest()->getPost());
        $model  = Mage::getModel('fstwofactorauth/admin_hash')
            ->loadByAdminId($post->getData('user_id'));

        $model->setMode($post->getData('twofactorauth/mode'))->save();

        return $this;
    }
}

