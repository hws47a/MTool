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
 * @copyright  Copyright (C) 2013 Vladimir Fishchenko (http://fishchenko.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Controller provider
 *
 * @category   Mtool
 * @package    Mtool_Providers
 * @author     Vladimir Fishchenko <vladimir@fishchenko.com>
 */
class Mtool_Providers_Design extends Mtool_Providers_Abstract
{
    /**
     * Get provider name
     * @return string
     */
    public function getName()
    {
        return 'mage-design';
    }

    /**
     * Add layout to config and create a file
     *
     * @param string $targetModule
     * @param string $configNamespace
     */
    protected function _addLayout($targetModule, $configNamespace)
    {
        if ($targetModule == null) {
            $targetModule = $this->_ask('Enter the target module (in format of Mycompany/Mymodule)');
        }

        list($companyName, $moduleName) = explode('/', $targetModule);


        $module = new Mtool_Codegen_Entity_Module(getcwd(), $moduleName, $companyName, $this->_getConfig());


        $entity = new Mtool_Codegen_Entity_Design();
        $entity->addLayout($module, $configNamespace);

        $this->_answer('Done');
    }

    /**
     * Add admin layout config and create a file
     *
     * @param string $targetModule like ModuleNamespace/ModuleName
     */
    public function addAdminLayout($targetModule = null)
    {
        $this->_addLayout($targetModule, 'adminhtml');
    }

    /**
     * Add frontend layout config and create a file
     *
     * @param string $targetModule like ModuleNamespace/ModuleName
     */
    public function addFrontendLayout($targetModule = null)
    {
        $this->_addLayout($targetModule, 'frontend');
    }
}
