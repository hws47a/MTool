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
 * @copyright  Copyright (C) 2012 Vladimir Fishchenko (http://fishchenko.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Controller provider
 *
 * @category   Mtool
 * @package    Mtool_Providers
 * @author     Vladimir Fishchenko <vladimir@fishchenko.com>
 */
class Mtool_Providers_Controller extends Mtool_Providers_Abstract
{
    /**
     * Get provider name
     * @return string
     */
    public function getName()
    {
        return 'mage-controller';
    }

    /**
     * Create controller
     *
     * @param string $targetModule in format of companyname/modulename
     * @param string $controllerPath
     */
    public function create($targetModule = null, $controllerPath = null)
    {
        //create controller file
        $this->_createEntity(new Mtool_Codegen_Entity_Controller(), 'controller', $targetModule, $controllerPath);
    }

    /**
     * Create entity
     *
     * @param Mtool_Codegen_Entity_Controller $entity
     * @param string $name
     * @param string $targetModule in format of companyname/modulename
     * @param string $entityPath in format of mymodule/model_path
     */
    protected function _createEntity($entity, $name, $targetModule = null, $entityPath = null)
    {
        if ($targetModule == null) {
            $targetModule = $this->_ask('Enter the target module (in format of Mycompany/Mymodule)');
        }
        if ($entityPath == null) {
            $entityPath = $this->_ask("Enter the {$name} path (in format of frontName/{$name}_path)");
        }

        list($companyName, $moduleName) = explode('/', $targetModule);

        $module = new Mtool_Codegen_Entity_Module(getcwd(), $moduleName, $companyName, $this->_getConfig());

        list($namespace, $entityName) = explode('/', $entityPath);

        $result = $entity->create($namespace, $entityName, $module);

        if ($result) {
            $this->_answer('Done');
            $this->_answer('Created class: ' . $result);
        } else {
            $this->_answer('Controller already exists');
        }
    }
}
