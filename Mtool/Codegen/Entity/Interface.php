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
interface Mtool_Codegen_Entity_Interface
{
    const RECOMMENDED_ZEND_CODING_STANDARD_LINE_LENGTH = 80;

    /**
     * Get entity config namespace
     * @return string
     */
    public function getConfigNamespace();

    /**
     * Create new entity
     * 
     * @param string $namespace 
     * @param string $path 
     * @param Mtool_Codegen_Entity_Module $module
     *
     * @return string
     */
    public function create($namespace, $path, Mtool_Codegen_Entity_Module $module);

    /**
     * Rewrite entity
     * 
     * @param string $originNamespace 
     * @param string $originPath 
     * @param string $path 
     * @param Mtool_Codegen_Entity_Module $module
     */
    public function rewrite($originNamespace, $originPath, $path, Mtool_Codegen_Entity_Module $module);

    /**
     * Lookup the class definition among the project
     * modules
     *
     * @param string $namespace
     * @param RegexIterator $configs
     * @param string $field
     * @return string (like Mage_Catalog_Model)
     */
    public function lookupOriginEntityClass($namespace, $configs, $field = 'class');

    /**
     * Create class file
     * 
     * @param string $path in format: class_path_string 
     * @param string $template 
     * @param Mtool_Codegen_Entity_Module $module
     * @param array $params
     *
     * @return string resulting class name
     */
    public function createClass($path, $template, $module, $params = array());
}
