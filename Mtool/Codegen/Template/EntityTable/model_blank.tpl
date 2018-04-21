<?php
/**
 * @category   Local
 * @package    #{company_name}_#{module_name}
 * @copyright  Copyright (C) #{year} #{copyright_company}
 * @author     #{author}
 */
class #{class_name} extends Mage_Core_Model_Abstract
{
    /**
     * Init the resource
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('#{namespace}/#{model_path}');
    }
}
