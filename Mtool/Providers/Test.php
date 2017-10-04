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
 * @package    Mtool_Providers
 * @copyright  Copyright (C) 2011 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Block provider
 *
 * @category   Mtool
 * @package    Mtool_Providers
 * @author     Daniel Kocherga <dan@oggettoweb.com>
 */
class Mtool_Providers_Test extends Mtool_Providers_Entity
{
    /**
     * Get provider name
     * @return string
     */
    public function getName()
    {
        return 'mage-test';
    }

    /**
     * Create block
     *
     * @param string $targetModule in format of companyname/modulename
     * @param string $testPath in format of entity_path
     */
    public function create($targetModule = null, $testPath = null)
    {
        $this->_createEntity(new Mtool_Codegen_Entity_Test(), 'test', $targetModule, 'test/' . $testPath);
    }

    /**
     * Create new block with module auto-guessing
     * @param string $type block model helper or other
     * @param string $entityPath used in magento in format of mymodule/entity_path
     */
    public function add($type = null, $entityPath = null)
    {
        //check data

        if ($type == null) {
            $type = $this->_ask("Enter the entity type like block, model, helper or other");
        }

        if ($entityPath == null) {
            $entityPath = $this->_ask("Enter the {$entityPath} path (in format of mymodule/{$entityType}_path)");
        }

        //get module
        list($namespace, $entityName) = explode('/', $entityPath);

        $module = null;
        $searchEntityClass = 'Mtool_Codegen_Entity_' . ucfirst($type);
        if (class_exists($searchEntityClass)) {
            $searchEntity = new $searchEntityClass();
            $module = Mtool_Codegen_Entity_Module_Finder::byNamespace(getcwd(), $searchEntity, $namespace, $this->_getConfig());
        }
        if ($module == null) {
            $targetModule = $this->_ask("Unfortunately module with {$type} namespace '{$namespace}' was not found. Enter the target module (in format of Mycompany/Mymodule)");
            list($companyName, $moduleName) = explode('/', $targetModule);
            $module = new Mtool_Codegen_Entity_Module(getcwd(), $moduleName, $companyName, $this->_getConfig());
        }

        $entityName = "{$type}_{$entityName}";

        $entity = new Mtool_Codegen_Entity_Test();
        $result = $entity->create($namespace, $entityName, $module);
        if ($result) {
            $this->_answer("New test created under {$module->getName()} module");
        } else {
            $this->_answer('Test already exists');
        }
    }
}
