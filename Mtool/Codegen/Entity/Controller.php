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
 * Controller code generator
 *
 * @category   Mtool
 * @package    Mtool_Codegen
 * @subpackage Entity
 * @author     Vladimir Fishchenko <vladimir@fishchenko.com>
 */
class Mtool_Codegen_Entity_Controller extends Mtool_Codegen_Entity_Abstract
{
    /**
     * Entity folder name
     * @var string
     */
    protected $_folderName = 'controllers';

    /**
     * Create template name
     * @var string
     */
    protected $_createTemplate = 'controller_blank';

    /**
     * Create new entity
     *
     * @param string $namespace
     * @param string $path
     * @param Mtool_Codegen_Entity_Module $module
     */
    public function create($namespace, $path, Mtool_Codegen_Entity_Module $module)
    {
        // Create class file
        $this->createClass($namespace, $path, $this->_createTemplate, $module);

        // Create namespace in config if not exist
//        $config = new Mtool_Codegen_Config($module->getConfigPath('config.xml'));
//        $configPath = "global/{$this->_configNamespace}/{$namespace}/class";
//        if (!$config->get($configPath)) {
//            $config->set($configPath, "{$module->getName()}_{$this->_entityName}");
//        }
    }

    /**
     * Create class file
     *
     * @param string $namespace: adminhtml or frontend
     * @param string $path in format: class_path_string
     * @param string $template
     * @param Mtool_Codegen_Entity_Module $module
     * @param array $params
     * @return string resulting class name
     */
    public function createClass($namespace, $path, $template, $module, $params = array())
    {
        //check controller namespace to
        // * set correct abstract method
        // * update controller path if needed

        $controllerAbstract = '';
        switch ($namespace) {
            case 'adminhtml':
            case 'admin':
                if (strpos($path, 'adminhtml_') !== 0) {
                    $path = 'adminhtml_' . $path;
                }
                $controllerAbstract = 'Mage_Adminhtml_Controller_Action';
                break;
            case 'frontend':
                $controllerAbstract = 'Mage_Core_Controller_Front_Action';
                break;
        }
        if (!$controllerAbstract) {
            throw Exception('Incorrect controller area');
        }

        $pathSteps = $this->_ucPath(explode('_', $path));
        $className = implode('_', $pathSteps);
        $classFilename = array_pop($pathSteps) . 'Controller.php';

        // Create class dir under module
        $classDir = Mtool_Codegen_Filesystem::slash($module->getDir()) . $this->_folderName .
            DIRECTORY_SEPARATOR .
            implode(DIRECTORY_SEPARATOR, $pathSteps);
        Mtool_Codegen_Filesystem::mkdir($classDir);

        // Move class template file
        $classTemplate = new Mtool_Codegen_Template($template);
        $resultingClassName = "{$module->getName()}_{$className}Controller";

        if (strlen('class ' . $resultingClassName) >= self::RECOMMENDED_ZEND_CODING_STANDARD_LINE_LENGTH) {
            $resultingClassName .= "\n   ";
        }

        $defaultParams = array(
            'company_name' => $module->getCompanyName(),
            'module_name' => $module->getModuleName(),
            'class_name' => $resultingClassName,
            'year' => date('Y'),
            'controller_abstract' => $controllerAbstract
        );

        $classTemplate->setParams(array_merge($defaultParams, $params, $module->getTemplateParams()));
        $classTemplate
            ->move($classDir, $classFilename);

        return $resultingClassName;
    }
}
