<?php
/**
 * Google Two-Factor Authentication.
 *
 * @package Freestream_GoogleTwoFa
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_GoogleTwoFa_Model_Admin_Observer
{
    /**
     * Login authentication.
     *
     * @param  Varien_Event_Observer $observer
     *
     * @return Freestream_GoogleTwoFa_Model_Admin_Observer
     */
    public function authenticateOtp(Varien_Event_Observer $observer)
    {
        $username   = $observer->getUsername();
        $post       = new Varien_Object(Mage::app()->getRequest()->getPost());

        Mage::getModel('fsgoogletwofa/admin_factory')
            ->authenticateOtp($username, $post->getData('login/googletwofa_otp'));

        return $this;
    }

    /**
     * Assign a secret key to admin user.
     *
     * @param  Varien_Event_Observer $observer
     *
     * @return Freestream_GoogleTwoFa_Model_Observer
     */
    public function assignSecretToAdmin(Varien_Event_Admin_Observer $observer)
    {
        $user   = $observer->getObject();
        $model  = Mage::getModel('fsgoogletwofa/admin_hash')
            ->loadByAdmin($user);

        if (!$model->getId()) {
            $secret = Mage::getModel('fsgoogletwofa/admin_factory')->generateSecretKey();
            $model->setHash($secret)->setMode(1)->save();
        }

        return $this;
    }

    /**
     * Save password mode for admin user.
     *
     * @param  Varien_Event_Observer $observer
     *
     * @return Freestream_GoogleTwoFa_Model_Observer
     */
    public function saveAdminSecret(Varien_Event_Admin_Observer $observer)
    {
        $post   = new Varien_Object(Mage::app()->getRequest()->getPost());
        $model  = Mage::getModel('fsgoogletwofa/admin_hash')
            ->loadByAdminId($post->getData('user_id'));

        $model->setMode($post->getData('googletwofa/mode'))->save();

        return $this;
    }
}

