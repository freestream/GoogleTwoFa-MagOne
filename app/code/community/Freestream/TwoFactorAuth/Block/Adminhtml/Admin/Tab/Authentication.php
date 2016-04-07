<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Anton Samuelsson
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
 * Two Factor Authentication Tab.
 *
 * @package Freestream_TwoFactorAuth
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_TwoFactorAuth_Block_Adminhtml_Admin_Tab_Authentication
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare form.
     *
     * @return Freestream_TwoFactorAuth_Block_Adminhtml_Admin_Tab_Authentication
     */
    protected function _prepareForm()
    {
        $model  = Mage::registry('permissions_user');
        $form   = new Varien_Data_Form();
        $helper = Mage::helper('fstwofactorauth');

        $form->setHtmlIdPrefix('fs_twofactorauth_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            array('legend' => $helper->__('Two-Factor Authentication Information'))
        );

        $fieldset->addField(
            'mode',
            'select',
            array(
                'label'     => $helper->__('Mode'),
                'name'      => 'twofactorauth[mode]',
                'values'    => Mage::getModel('fstwofactorauth/system_source_mode')->toOptionHash()
            )
        );

        $fieldset->addType(
            'button',
            Mage::getConfig()->getBlockClassName('fstwofactorauth/adminhtml_permissions_user_renderer_button')
        );

        $generationUrl  = $this->getUrl('*/twofa/generate', array('user_id' => $model->getUserId()));
        $message        = $helper->__('Are you sure?');

        $fieldset->addField(
            'generate_secret',
            'button',
            array(
                'name'          => 'generate_secret',
                'label'         => Mage::helper('fstwofactorauth')->__('Generate New Secret'),
                'onclick'       => "confirmSetLocation('{$message}', '{$generationUrl}')",
                'button_label'  => $helper->__('Generate'),
            )
        );

        $fieldset->addField(
            'secret',
            'label',
            array(
                'label'     => $helper->__('Secret Key'),
            )
        );

        $fieldset->addType(
            'display_image',
            Mage::getConfig()->getBlockClassName('fstwofactorauth/adminhtml_permissions_user_renderer_qr')
        );

        $fieldset->addField(
            'qr_code',
            'display_image',
            array(
                'name'      => 'qr_code',
                'label'     => $helper->__('QR Code'),
            )
        );

        if ($model->getUserId()) {
            $secretModel = Mage::getSingleton('fstwofactorauth/admin_hash')
                ->loadByAdmin($model);

            $form->setValues(
                array(
                    'secret'    => $secretModel->getHash(),
                    'mode'      => $secretModel->getMode(),
                )
            );
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Tab label.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('fstwofactorauth')->__('Authentication');
    }

    /**
     * Tab title.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('fstwofactorauth')->__('Authentication');
    }

    /**
     * Checks if tab can be shown.
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return (boolean) (integer) Mage::registry('permissions_user')->getUserId();
    }

    /**
     * Checks if tab should be hidden.
     *
     * @return boolean
     */
    public function isHidden()
    {
        return !$this->canShowTab();
    }
}

