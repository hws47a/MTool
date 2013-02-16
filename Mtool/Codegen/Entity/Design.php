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
 * @copyright  Copyright (C) 2013 Vladimir Fishchenko (http://fishchenko.com/)
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
class Mtool_Codegen_Entity_Design extends Mtool_Codegen_Entity_Abstract
{
    const ADMINHTML_PACKAGE = 'default';
    const ADMINHTML_THEME = 'default';
    const FRONTEND_PACKAGE = 'base';
    const FRONTEND_THEME = 'default';

    /**
     * Allowed config namespaces
     *
     * @var array
     */
    protected $_allowedConfigNamespaces = array();

    /**
     * Add allowed config namespaces
     */
    public function __construct()
    {
        $this->_allowedConfigNamespaces = array('adminhtml', 'frontend');
    }

    /**
     * Add layout to config and create a file
     *
     * @param Mtool_Codegen_Entity_Module $module
     * @param string                      $configNamespace 'adminhtml or frontend'
     *
     * @return bool
     */
    public function addLayout(Mtool_Codegen_Entity_Module $module, $configNamespace)
    {
        if (!in_array($configNamespace, $this->_allowedConfigNamespaces)) {
            return false;
        }

        $fileName = $this->_getLayoutFileName($module, $configNamespace);

        $this->_createLayoutFile($module, $configNamespace, $fileName);

        return true;
    }

    /**
     * Get Layout Package
     *
     * @param string $configNamespace
     *
     * @return string
     */
    public function getPackage($configNamespace){
        switch ($configNamespace) {
            case 'adminhtml':
                $package = self::ADMINHTML_PACKAGE;
                break;
            case 'frontend':
                $package = self::FRONTEND_PACKAGE;
                break;
            default:
                $package = '';
        }

        return $package;
    }

    /**
     * Get Layout Theme
     *
     * @param string $configNamespace
     *
     * @return string
     */
    public function getTheme($configNamespace){
        switch ($configNamespace) {
            case 'adminhtml':
                $package = self::ADMINHTML_THEME;
                break;
            case 'frontend':
                $package = self::FRONTEND_THEME;
                break;
            default:
                $package = '';
        }

        return $package;
    }

    /**
     * Get Layout File Path
     *
     * @param Mtool_Codegen_Entity_Module $module
     * @param string                      $configNamespace
     *
     * @return string
     */
    public function getLayoutFilePath(Mtool_Codegen_Entity_Module $module, $configNamespace)
    {
        return $this->_getLayoutDir($module, $configNamespace) . DIRECTORY_SEPARATOR
            . $this->_getLayoutFileName($module, $configNamespace);
    }

    /**
     * Get Layout File Name
     *
     * @param Mtool_Codegen_Entity_Module $module
     * @param string                      $configNamespace
     *
     * @return string
     */
    protected function _getLayoutFileName(Mtool_Codegen_Entity_Module $module, $configNamespace)
    {
        $config = new Mtool_Codegen_Config($module->getConfigPath('config.xml'));
        $namespace = strtolower($module->getCompanyName() . '_' . $module->getModuleName());
        $layoutFilePath = $configNamespace . '/layout/updates/' . $namespace . '/file';
        $fileName = $config->get($layoutFilePath);
        if (!$fileName) {
            $fileName = $namespace . '.xml';
            $config->set($layoutFilePath, $fileName);
        }

        return $fileName;
    }

    /**
     * Get Layout File Directory
     *
     * @param Mtool_Codegen_Entity_Module $module
     * @param string                      $configNamespace
     *
     * @return string
     * @throws Mtool_Codegen_Exception_Module
     */
    protected function _getLayoutDir(Mtool_Codegen_Entity_Module $module, $configNamespace)
    {
        $package = $this->getPackage($configNamespace);
        $theme = $this->getTheme($configNamespace);
        switch ($configNamespace) {
            case 'adminhtml':
                $designPath = $module->getMage()->getDesignAdminhtmlPath($package, $theme);
                break;
            case 'frontend':
                $designPath = $module->getMage()->getDesignFrontendPath($package, $theme);
                break;
            default:
                throw new Mtool_Codegen_Exception_Module('Incorrect config namespace');
        }
        return $designPath . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR;
    }

    /**
     * Create Layout File
     *
     * @param Mtool_Codegen_Entity_Module $module
     * @param string                      $configNamespace
     * @param string                      $fileName
     */
    protected function _createLayoutFile(Mtool_Codegen_Entity_Module $module, $configNamespace, $fileName)
    {
        $filePath = $this->_getLayoutDir($module, $configNamespace);

        // Check that module does not already exist
        if (Mtool_Codegen_Filesystem::exists($filePath . $fileName)) {
            return;
        }

        $params = array(
            'module_name' => $module->getName(),
            'module' => $module->getName(),
            'company_name' => $module->getCompanyName(),
            'year' => date('Y'),
            'package' => $this->getPackage($configNamespace),
            'theme' => $this->getTheme($configNamespace)
        );

        // Create layout file
        $configTemplate = new Mtool_Codegen_Template('layout_empty');
        $configTemplate
            ->setParams(array_merge($params, $module->getTemplateParams()))
            ->move($filePath, $fileName);
    }
}
