<?php 
/**
 * Mage Tool
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Mtool
 * @package    Mtool_Codegen
 * @copyright  Copyright (C) 2011 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Crud code generator
 *
 * @category   Mtool
 * @package    Mtool_Codegen
 * @subpackage Entity
 * @author     Vladimir Fishchenko <vladimir@fishchenko.com>
 */
class Mtool_Codegen_Entity_Crud extends Mtool_Codegen_Entity_Abstract
{
    /**
     * block folder name
     * @var string
     */
    protected $_blockFolderName = 'Block';
    protected $_folderName = 'Block';

    /**
     * model folder name
     * @var string
     */
    protected $_modelFolderName = 'Model';

    protected $_entityTemplateDir = 'Crud';

    /**
     * Grid container template name
     * @var string
     */
    protected $_gridContainerTemplate = 'block_grid_container';

    /**
     * Form container template name
     * @var string
     */
    protected $_formContainerTemplate = 'block_form_container';

    /**
     * Grid template name
     * @var string
     */
    protected $_gridTemplate = 'block_grid';

    /**
     * Form template name
     * @var string
     */
    protected $_formTemplate = 'block_form';

    /**
     * Controller template name
     * @var string
     */
    protected $_controllerTemplate = 'controller';

    /**
     * Block Entity name
     * @var string
     */
    protected $_blockEntityName = 'Block';
    protected $_entityName = 'Block';

    /**
     * Model Entity name
     * @var string
     */
    protected $_modelEntityName = 'Model';

    /**
     * Block Namespace in config file
     * @var string
     */
    protected $_blockConfigNamespace = 'blocks';

    /**
     * Model Namespace in config file
     * @var string
     */
    protected $_modelConfigNamespace = 'models';


    /**
     * Create new entity
     *
     * @param string $namespace
     * @param string $path
     * @param Mtool_Codegen_Entity_Module $module
     */
    public function createGridBlock(Mtool_Codegen_Entity_Module $module, $blockNS, $blockName, $modelNS, $modelName)
    {
        $params = array(
            'block_ns' => $blockNS,
            'block_path' => $blockName,
            'model_ns' => $modelNS,
            'model_path' => $modelName,
            'header_text' => $module->getModuleName() . ' ' . str_replace('_', ' ', ucfirst($modelName))
        );

        // Create grid container class file
        $this->createClass(
            $blockName,
            $this->_entityTemplateDir . DIRECTORY_SEPARATOR . $this->_gridContainerTemplate,
            $module,
            $params
        );

        // Create grid class file
        $this->createClass(
            $blockName . '_grid',
            $this->_entityTemplateDir . DIRECTORY_SEPARATOR . $this->_gridTemplate,
            $module,
            $params
        );

        $this->_createNamespaces($module, $blockNS, $modelNS);
    }

    /**
     * Create new entity
     *
     * @param string $namespace
     * @param string $path
     * @param Mtool_Codegen_Entity_Module $module
     */
    public function createFormBlock(Mtool_Codegen_Entity_Module $module, $blockNS, $blockName, $modelNS, $modelName)
    {
        $params = array(
            'block_ns' => $blockNS,
            'block_path' => $blockName,
            'model_ns' => $modelNS,
            'model_path' => $modelName,
        );

        // Create form container class file
        $this->createClass(
            $blockName . '_edit',
            $this->_entityTemplateDir . DIRECTORY_SEPARATOR . $this->_formContainerTemplate,
            $module,
            $params
        );

        // Create form class file
        $this->createClass(
            $blockName . '_edit_form',
            $this->_entityTemplateDir . DIRECTORY_SEPARATOR . $this->_formTemplate,
            $module,
            $params
        );

        $this->_createNamespaces($module, $blockNS, $modelNS);
    }

    /**
     * Create new entity
     *
     * @param string $namespace
     * @param string $path
     * @param Mtool_Codegen_Entity_Module $module
     */
    public function createController(Mtool_Codegen_Entity_Module $module, $controllerNS, $controllerName, $modelNS, $modelName)
    {
        $controllerEntity = new Mtool_Codegen_Entity_Controller();

        $params = array(
            'model_ns' => $modelNS,
            'model_path' => $modelName,
            'header_text' => $module->getModuleName() . ' ' . str_replace('_', ' ', ucfirst($modelName))
        );

        // Create controller class file
        $controllerEntity->create(
            $controllerNS,
            $controllerName,
            $module,
            $this->_entityTemplateDir . DIRECTORY_SEPARATOR . $this->_controllerTemplate,
            $params
        );
    }

    protected function _createNamespaces(Mtool_Codegen_Entity_Module $module, $blockNS, $modelNS)
    {
        // Create block namespace in config if not exist
        $config = new Mtool_Codegen_Config($module->getConfigPath('config.xml'));
        $configPath = "global/{$this->_blockConfigNamespace}/{$blockNS}/class";
        if (!$config->get($configPath)) {
            $config->set($configPath, "{$module->getName()}_{$this->_blockEntityName}");
        }

        // Create model namespace in config if not exist
        $config = new Mtool_Codegen_Config($module->getConfigPath('config.xml'));
        $configPath = "global/{$this->_modelConfigNamespace}/{$modelNS}/class";
        if (!$config->get($configPath)) {
            $config->set($configPath, "{$module->getName()}_{$this->_modelEntityName}");
        }
    }
}
