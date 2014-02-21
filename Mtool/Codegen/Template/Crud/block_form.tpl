<?php
/**
 * #{module_owner} extension for Magento
 *
 * Long description of this file (if any...)
 *
 * NOTICE OF LICENSE
 *
#{license}
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the #{company_name} #{module_name} module to newer versions in the future.
 * If you wish to customize the #{company_name} #{module_name} module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   #{company_name}
 * @package    #{company_name}_#{module_name}
 * @copyright  Copyright (C) #{year} #{copyright_company}
 * @license    #{license_short}
 */

/**
 * Short description of the class
 *
 * Long description of the class (if any...)
 *
 * @category   #{company_name}
 * @package    #{company_name}_#{module_name}
 * @subpackage Block
 * @author     #{author}
 */
class #{class_name} extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare the form
     *
     * @return Mage_Adminhtml_Block_Widget_Form|void
     */
    protected function _prepareForm()
    {
        //add form
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id', null))),
            'method' => 'post'
        ));
        $form->setUseContainer(true);
        $this->setForm($form);

        //add fieldset
        $fieldSet = $form->addFieldset(
            'main_field_set',
            array('legend' => $this->__('Main Content'))
        );

        //ADD FIELDS HERE

        $data = Mage::registry('current_#{model_ns}_#{model_path}');
        if ($data) {
            $form->setValues($data->getData());
        }

        parent::_prepareForm();
    }
}
