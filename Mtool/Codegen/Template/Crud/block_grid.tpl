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
class #{class_name} extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Init grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('#{model_path}Grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);

        $this->setModelPath('#{model_ns}/#{model_path}');
        $this->setIdFieldName(Mage::getModel($this->getModelPath())->getResource()->getIdFieldName());
    }

    /**
     * prepare collection for grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel($this->getModelPath())->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid columns
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn($this->getIdFieldName(), array(
            'header'    =>  $this->__('ID'),
            'align'     =>  'right',
            'width'     =>  '50px',
            'type'      =>  'int',
            'index'     =>  $this->getIdFieldName()
        ));

        return parent::_prepareColumns();
    }

    /**
     * get the row url for edit
     *
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * prepare mass action methods
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField($this->getIdFieldName());
        $this->getMassactionBlock()->setFormFieldName('menu');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => $this->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => $this->__('Are you sure?')
        ));

        return parent::_prepareMassaction();
    }

    /**
     * get the grid url for ajax updates
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}
