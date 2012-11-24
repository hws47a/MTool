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
 * CRUD provider
 *
 * @category   Mtool
 * @package    Mtool_Providers
 * @author     Vladimir Fishchenko <vladimir@fishchenko.com>
 */
class Mtool_Providers_Crud extends Mtool_Providers_Abstract
{
    /**
     * Get provider name
     * @return string
     */
    public function getName()
    {
        return 'mage-crud';
    }

    /**
     * Create grid block
     *
     * @param   string  $targetModule   in format of companyname/modulename
     * @param   string  $blockPath      in format of mymodule/block_path
     * @param   string  $modelPath      in format of mymodule/model_path
     */
    public function createGridBlock($targetModule = null, $blockPath = null, $modelPath = null)
    {
        $this->_createGridBlock($targetModule, $blockPath, $modelPath);
    }

    /**
     * Create entity
     *
     * @param   string  $targetModule   in format of companyname/modulename
     * @param   string  $blockPath      in format of mymodule/block_path
     * @param   string  $modelPath      in format of mymodule/model_path
     */
    protected function _createGridBlock($targetModule = null, $blockPath = null, $modelPath = null)
    {
        $entity = new Mtool_Codegen_Entity_Crud();

        if ($targetModule == null) {
            $targetModule = $this->_ask('Enter the target module (in format of Mycompany/Mymodule)');
        }
        if ($blockPath == null) {
            $entityPath = $this->_ask("Enter the block path (in format of mymodule/block_path)");
        }
        if ($modelPath == null) {
            $entityPath = $this->_ask("Enter the model path (in format of mymodule/model_path)");
        }

        list($companyName, $moduleName) = explode('/', $targetModule);

        $module = new Mtool_Codegen_Entity_Module(getcwd(), $moduleName, $companyName, $this->_getConfig());

        list($blockNS, $blockName) = explode('/', $blockPath);

        list($modelNS, $modelName) = explode('/', $modelPath);

        $entity->createGridBlock($module, $blockNS, $blockName, $modelNS, $modelName);

        $this->_answer('Done');
    }
}
