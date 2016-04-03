<?php
/**
 * Button renderer.
 *
 * @package Freestream_GoogleTwoFa
 * @module  Freestream
 * @author  Anton Samuelsson <samuelsson.anton@gmail.com>
 */
class Freestream_GoogleTwoFa_Block_Adminhtml_Permissions_User_Renderer_Button
    extends Varien_Data_Form_Element_Abstract
{
    /**
     * Button element HTML.
     *
     * @return string
     */
    public function getElementHtml()
    {
        return Mage::app()->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(
                array(
                    'name'      => $this->getName(),
                    'label'     => $this->getButtonLabel(),
                    'onclick'   => $this->getOnclick(),
                    'class'     => $this->getClass(),
                )
            )
            ->toHtml();
    }
}
