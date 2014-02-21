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
 * Model provider
 *
 * @category   Mtool
 * @package    Mtool_Providers
 * @author     Vladimir Fishchenko <vladimir@fishchenko.com>
 */
class Mtool_Providers_TableEntity extends Mtool_Providers_Abstract
{
    /**
     * Get provider name
     * @return string
     */
    public function getName()
    {
        return 'mage-table-entity';
    }

    /**
     * Create table entity
     *
     * @param string $targetModule in format of companyname/modulename
     * @param string $entityPath in format of mymodule/model_path
     * @param string $addTableName table name
     */
    public function create($targetModule = null, $entityPath = null, $addTableName = null)
    {
        $this->_createEntity($targetModule, $entityPath, $addTableName);
    }

    /**
     * Create entity
     *
     * @param string $targetModule in format of companyname/modulename
     * @param null   $entityPath
     * @param string $tableName    - table name
     */
    protected function _createEntity($targetModule = null, $entityPath = null, $tableName = null)
    {
        $entity = new Mtool_Codegen_Entity_TableEntity();
        if ($targetModule == null) {
            $targetModule = $this->_ask('Enter the target module (in format of Mycompany/Mymodule)');
        }
        if ($entityPath == null) {
            $entityPath = $this->_ask("Enter the model path (in format of mymodule/model_path)");
        }
        if ($tableName == null) {
            $tableName = $this->_ask("Enter the table name");
        }

        list($companyName, $moduleName) = explode('/', $targetModule);

        $module = new Mtool_Codegen_Entity_Module(getcwd(), $moduleName, $companyName, $this->_getConfig());

        list($namespace, $entityName) = explode('/', $entityPath);

        $errors = $entity->create($namespace, $entityName, $module, $entityName, $tableName);

        if (empty($errors)) {
            $this->_answer('Done');
        } else {
            foreach ($errors as $_error) {
                $this->_answer($_error);
            }
        }
    }
}
