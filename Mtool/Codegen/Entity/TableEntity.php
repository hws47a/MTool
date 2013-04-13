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
 * @copyright  Copyright (C) 2012 Vladimir Fishchenko (http://fishchenko.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Model code generator
 *
 * @category   Mtool
 * @package    Mtool_Codegen
 * @subpackage Entity
 * @author     Vladimir Fishchenko <vladimir@fishchenko.com>
 */
class Mtool_Codegen_Entity_TableEntity extends Mtool_Codegen_Entity_Abstract
{
    /**
     * Entity folder name
     * @var string
     */
    protected $_folderName = 'Model';

    /**
     * template path
     *
     * @var string
     */
    protected $_templatePath = 'EntityTable';

    /**
     * Model template name
     * @var string
     */
    protected $_modelTemplate = 'model_blank';

    /**
     * Resource model template name
     * @var string
     */
    protected $_rModelTemplate = 'rmodel_blank';

    /**
     * Collection template name
     * @var string
     */
    protected $_collectionTemplate = 'collection_blank';

    /**
     * Entity name
     * @var string
     */
    protected $_entityName = 'Model';

    /**
     * Namespace in config file
     * @var string
     */
    protected $_configNamespace = 'models';

    /**
     * Create new entity
     *
     * @param string $namespace
     * @param string $path
     * @param Mtool_Codegen_Entity_Module $module
     * @param string $entityName
     * @param string $tableName
     *
     * @return array errors
     */
    public function create($namespace, $path, Mtool_Codegen_Entity_Module $module, $entityName, $tableName)
    {
        $errors = array();

        // Create namespace in config if not exist
        $config = new Mtool_Codegen_Config($module->getConfigPath('config.xml'));
        $configPath = "global/{$this->_configNamespace}/{$namespace}/class";
        if (!$config->get($configPath)) {
            $config->set($configPath, "{$module->getName()}_{$this->_entityName}");
        }

        //Create resource model namespace in config if not exist
        $configPath = "global/{$this->_configNamespace}/{$namespace}/resourceModel";
        $resourceNamespace = $config->get($configPath);
        if (!$resourceNamespace) {
            $resourceNamespace = "{$namespace}_resource";
            $config->set($configPath, $resourceNamespace);
        }
        $resourceConfigPath = "global/{$this->_configNamespace}/{$resourceNamespace}/class";
        if (!$config->get($resourceConfigPath)) {
            $config->set($resourceConfigPath, "{$module->getName()}_{$this->_entityName}_Resource");
        }

        //Create table entity
        $configPath = "global/{$this->_configNamespace}/{$resourceNamespace}/entities/{$entityName}/table";
        $config->set($configPath, $tableName);


        // Create model file
        $template = $this->_templatePath . DIRECTORY_SEPARATOR . $this->_modelTemplate;
        $res = $this->createClass($path, $template, $module, array('namespace' => $namespace, 'model_path' => $path));
        if (!$res) {
            $errors[] = "Model already exists";
        }

        // Create resource model file
        $resourcePrefix = $config->get($resourceConfigPath);
        $resourcePrefix = substr($resourcePrefix, strlen("{$module->getName()}_{$this->_entityName}_"));
        $resourcePrefix = strtolower($resourcePrefix) . '_';

        $template = $this->_templatePath . DIRECTORY_SEPARATOR . $this->_rModelTemplate;
        $res = $this->createClass($resourcePrefix . $path, $template, $module,
            array('namespace' => $namespace, 'entity' => $entityName));
        if (!$res) {
            $errors[] = "Resource model already exists";
        }

        // Create collection file
        $template = $this->_templatePath . DIRECTORY_SEPARATOR . $this->_collectionTemplate;
        $res = $this->createClass($resourcePrefix . $path . '_collection', $template, $module,
            array('namespace' => $namespace, 'model_path' => $path));
        if (!$res) {
            $errors[] = "Collection already exists";
        }

        return $errors;
    }
}
