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
class #{class_name} extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Init grid container
     */
    public function __construct()
    {
        parent::__construct();
        $this->_controller = '#{block_path}';
        $this->_blockGroup = '#{block_ns}';
        $this->_headerText = $this->__('#{header_text}');
    }
}
