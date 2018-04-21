<?php
/**
 * @category   Local
 * @package    #{company_name}_#{module_name}
 * @copyright  Copyright (C) #{year} #{copyright_company}
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
