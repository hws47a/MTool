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
 * @subpackage Model
 * @author     #{author}
 */
class #{class_name} extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Init model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('#{namespace}/#{model_path}');
    }
}
