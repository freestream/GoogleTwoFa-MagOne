<?php
/**
 * Google Two Factor Authentication Tab.
 *
 * @package Freestream_GoogleTwoFa
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_GoogleTwoFa_Block_Adminhtml_Admin_Tab_Authentication
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare form.
     *
     * @return Freestream_GoogleTwoFa_Block_Adminhtml_Admin_Tab_Authentication
     */
    protected function _prepareForm()
    {
        $model  = Mage::registry('permissions_user');
        $form   = new Varien_Data_Form();

        $form->setHtmlIdPrefix('fs_googletwofa_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            array('legend' => Mage::helper('fsgoogletwofa')->__('Two-Factor Authentication Information'))
        );

        $fieldset->addField(
            'mode',
            'select',
            array(
                'label'                 => Mage::helper('fsgoogletwofa')->__('Mode'),
                'name'                  => 'googletwofa[mode]',
                'values'                => array(
                    '1' => 'Password Only',
                    '2' => 'Password and One-time Password',
                ),
            )
        );

        $fieldset->addType(
            'button',
            Mage::getConfig()->getBlockClassName('fsgoogletwofa/adminhtml_permissions_user_renderer_button')
        );

        $generationUrl  = $this->getUrl('*/googletwofa/generate', array('user_id' => $model->getUserId()));
        $message        = Mage::helper('fsgoogletwofa')->__('Are you sure?');

         $fieldset->addField(
            'generate_secret',
            'button',
            array(
                'name'          => 'generate_secret',
                'label'         => Mage::helper('fsgoogletwofa')->__('Generate New Secret'),
                'onclick'       => "confirmSetLocation('{$message}', '{$generationUrl}')",
                'button_label'  => Mage::helper('fsgoogletwofa')->__('Generate'),
            )
        );

        $fieldset->addField(
            'secret',
            'label',
            array(
                'label'     => Mage::helper('fsgoogletwofa')->__('Secret Key'),
            )
        );

        $fieldset->addType(
            'display_image',
            Mage::getConfig()->getBlockClassName('fsgoogletwofa/adminhtml_permissions_user_renderer_qr')
        );

         $fieldset->addField(
            'qr_code',
            'display_image',
            array(
                'name'      => 'qr_code',
                'label'     => Mage::helper('fsgoogletwofa')->__('QR Code'),
            )
        );

        if ($model->getUserId()) {
            $secretModel = Mage::getSingleton('fsgoogletwofa/admin_hash')
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
        return Mage::helper('fsgoogletwofa')->__('Authentication');
    }

    /**
     * Tab title.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('fsgoogletwofa')->__('Authentication');
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

