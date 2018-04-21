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
class Freestream_TwoFactorAuth_Adminhtml_TwofaController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Generate new secret for admin user.
     */
    public function generateAction()
    {
        $secret = Mage::getModel('fstwofa/admin_factory')->generateSecretKey();
        $userId = $this->getRequest()->getParam('user_id');

        Mage::getModel('fstwofa/admin_hash')
            ->loadByAdminId($userId)
            ->setHash($secret)
            ->save();

        $this->_redirect('*/permissions_user/edit', array('user_id' => $userId));
    }

    /**
     * Only for allowed users.
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/acl/users');
    }
}

