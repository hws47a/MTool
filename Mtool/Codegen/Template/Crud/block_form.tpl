<?php
/**
 * @category   Local
 * @package    #{company_name}_#{module_name}
 * @copyright  Copyright (C) #{year} #{copyright_company}
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
