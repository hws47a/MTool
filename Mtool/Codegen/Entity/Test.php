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
 * @copyright  Copyright (C) 2017 Vladimir Fishchenko (http://fishchenko.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Block code generator
 *
 * @category   Mtool
 * @package    Mtool_Codegen
 * @subpackage Entity
 * @author     Vladimir Fishchenko <vladimir@fishchenko.com>
 */
class Mtool_Codegen_Entity_Test extends Mtool_Codegen_Entity_Abstract
{
    /**
     * Entity folder name
     * @var string
     */
    protected $_folderName = 'Test';

    /**
     * Create template name
     * @var string
     */
    protected $_createTemplate = 'test_blank';

    /**
     * Entity name
     * @var string
     */
    protected $_entityName = 'Test';

    /**
     * Create new entity
     *
     * @param string $namespace
     * @param string $path
     * @param Mtool_Codegen_Entity_Module $module
     *
     * @return string
     */
    public function create($namespace, $path, Mtool_Codegen_Entity_Module $module)
    {
        // Create class file
        $className = $this->createClass($path, $this->_createTemplate, $module);
        if (!$className) {
            return '';
        }

        // Create namespace in config if not exist
        $config = new Mtool_Codegen_Config($module->getConfigPath('config.xml'));
        $configPath = "phpunit/suite/modules/{$module->getName()}";
        if (!$config->get($configPath)) {
            $config->set($configPath, null);
        }

        return $className;
    }
}
