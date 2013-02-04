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
