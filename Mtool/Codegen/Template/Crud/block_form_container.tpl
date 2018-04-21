<?php
/**
 * @category   Local
 * @package    #{company_name}_#{module_name}
 * @copyright  Copyright (C) #{year} #{copyright_company}
 * @author     #{author}
 */
class #{class_name} extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Init grid container
     */
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_controller = '#{block_path}';
        $this->_blockGroup = '#{block_ns}';
    }

    /**
     * Get container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        $data = Mage::registry('current_#{model_ns}_#{model_path}');
        if ($data) {
            return $this->__('Edit Item #%d', $this->escapeHtml($data->getId()));
        } else {
            return $this->__('New Item');
        }
    }
}
