<?php
/**
 * @category   Local
 * @package    #{company_name}_#{module_name}
 * @copyright  Copyright (C) #{year} #{copyright_company}
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
