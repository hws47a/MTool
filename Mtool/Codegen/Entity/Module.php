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
 * @copyright  Copyright (C) 2011 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Module code generator
 *
 * @category   Mtool
 * @package    Mtool_Codegen
 * @subpackage Entity
 * @author     Daniel Kocherga <dan@oggettoweb.com>
 */
class Mtool_Codegen_Entity_Module extends Mtool_Codegen_Entity_Abstract
{
    /*
     * Upgrade modes
     */
    const UPGRADE_MODE_EXACT = 'to';
    const UPGRADE_MODE_INCREMENT = 'inc';

    /**
     * Magento configuration class
     *
     * @var Mtool_Magento
     */
    protected $_mage;

    /**
     * Module name
     * @var string
     */
    protected $_moduleName;

    /**
     * Company name
     * @var string
     */
    protected $_companyName;

    /**
     * Module dir path
     * @var string
     */
    protected $_moduleDir;

    /**
     * Module configs (etc) dir path
     * @var string
     */
    protected $_moduleConfigsDir;

    /**
     * Module sql dir path
     * @var string
     */
    protected $_moduleSqlDir;

    /**
     * Params to substitude in templates
     *
     * @var array
     */
    protected $_templateParams = array();

    /**
     * Init environemnt
     *
     * @param string $root absolute path to magento root
     * @param string $moduleName
     * @param string $companyName
     * @param array $params (such as author, license etc.) to substitude in templates
     */
    public function __construct($root, $moduleName, $companyName, array $params)
    {
        $this->_mage = new Mtool_Magento($root);

        $this->_moduleName = ucfirst($moduleName);
        $this->_companyName = ucfirst($companyName);
        $this->_templateParams = $params;

        $this->_moduleDir = $this->_mage->getCodepoolPath() 
            . $this->_companyName 
            . DIRECTORY_SEPARATOR 
            . $this->_moduleName
        ;

        $this->_moduleConfigsDir = $this->_moduleDir . DIRECTORY_SEPARATOR . 'etc';
        $this->_moduleSqlDir = $this->_moduleDir . DIRECTORY_SEPARATOR . 'sql';
    }

    /**
     * Get Magento
     *
     * @return Mtool_Magento
     */
    public function getMage()
    {
        return $this->_mage;
    }

    /**
     * Create dummy module:
     *     1. create module folder under app/code/local
     *     2. create module config.xml file
     *     3. create module file under app/etc/modules
     */
    public function createDummy()
    {
        // Check that module does not already exist
        if ($this->exists())
            throw new Mtool_Codegen_Exception_Module(
                "Seems like this module already exists. Aborting."
            );

        // Create module dir
        Mtool_Codegen_Filesystem::mkdir($this->_moduleDir);

        $name = $this->getName();

        $params = array(
            'module_name' => $name,
            'module' => $this->_moduleName,
            'company_name' => $this->getCompanyName(),
            'year' => date('Y'),
        );

        // Create config.xml file
        $configTemplate = new Mtool_Codegen_Template('module_config_empty');
        $configTemplate
                ->setParams(array_merge($params, $this->_templateParams))
                ->move($this->_moduleConfigsDir, 'config.xml');

        // Create module file under app/etc/modules
        $modulesTemplate = new Mtool_Codegen_Template('module_etc');
        $modulesTemplate
                ->setParams(array_merge($params, $this->_templateParams))
                ->move($this->_mage->getModulesConfigPath(), "{$name}.xml");
    }

    /**
     * Create module installer:
     * 1. create version entry in the config
     * 2. create setup resource entry in config
     * 3. create installer file under module sql directory
     *
     * @param string $version - initial version in format 1.0.0
     */
    public function install($version)
    {
        if (!$this->exists())
            throw new Mtool_Codegen_Exception_Module(
                "Seems like this module does not exist. Aborting."
            );

        $config = new Mtool_Codegen_Config($this->getConfigPath('config.xml'));

        // Create version entry in config
        $config->set("modules/{$this->getName()}/version", $version);

        // Create setup resource entry in config
        $setupNamspace = strtolower($this->getName()) . '_setup';
        $config->set("global/resources/{$setupNamspace}/setup/module", $this->getName());
        $config->set("global/resources/{$setupNamspace}/setup/connection", 'core_setup');

        // Create installer file
        $modulesTemplate = new Mtool_Codegen_Template('module_installer');

        $params = array(
            'module_name' => $this->getName(),
            'module' => $this->_moduleName,
            'company_name' => $this->getCompanyName(),
            'year' => date('Y'),
            'class' => ($config->get("global/resources/{$setupNamspace}/setup/class"))
                    ? : 'Mage_Core_Model_Resource_Setup',
        );

        $modulesTemplate
                ->setParams(array_merge($params, $this->_templateParams))
                ->move($this->_moduleSqlDir . DIRECTORY_SEPARATOR . $setupNamspace,
                       "mysql4-install-{$version}.php");
    }

    /**
     * Upgrade magento module
     *
     * @param string $mode - see mode constants
     * @param string $versionRequest - exact version or a mask depending on mode
     */
    public function upgrade($mode, $versionRequest)
    {
        if (!$this->exists())
            throw new Mtool_Codegen_Exception_Module(
                "Seems like this module does not exist. Aborting."
            );

        $config = new Mtool_Codegen_Config($this->getConfigPath('config.xml'));

        // Define version value
        $currentVersion = $config->get("modules/{$this->getName()}/version");
        switch ($mode) {
            case self::UPGRADE_MODE_EXACT:
                $version = $versionRequest;
                break;
            case self::UPGRADE_MODE_INCREMENT:
                $version = $this->_forceVersion($currentVersion, $versionRequest);
                break;
            default:
                throw new Mtool_Codegen_Exception_Module(
                    "Undefined version upgrade type: {$mode}"
                );
        }
        $config->set("modules/{$this->getName()}/version", $version);

        // Create upgrade file
        $setupNamspace = strtolower($this->getName()) . '_setup';
        if (!$config->get("global/resources/{$setupNamspace}")) {
            $xml = $config->getXml()->xpath("global/resources/*/setup/module[text() = '{$this->getName()}']/../..");
            if (count($xml)) {
                $setupNamspace = $xml[0]->getName();
            }
        }

        $modulesTemplate = new Mtool_Codegen_Template('module_upgrader');

        $params = array(
            'module_name' => $this->getName(),
            'module' => $this->_moduleName,
            'company_name' => $this->getCompanyName(),
            'year' => date('Y'),
            'class' => ($config->get("global/resources/{$setupNamspace}/setup/class"))
                    ? : 'Mage_Core_Model_Resource_Setup',
        );

        $modulesTemplate
                ->setParams(array_merge($params, $this->_templateParams))
                ->move($this->_moduleSqlDir . DIRECTORY_SEPARATOR . $setupNamspace,
                       "mysql4-upgrade-{$currentVersion}-{$version}.php");
    }

    /**
     * Force version with increment by mask
     *
     * @param string $version input value
     * @param string $mask in format of *.*.1
     *  where * means same value as in input
     * @return string
     */
    protected function _forceVersion($version, $mask)
    {
        $maskSegments = explode('.', $mask);
        $versionSegments = explode('.', $version);
        foreach ($versionSegments as $_index => &$_segment)
            if (isset($maskSegments[$_index]) && $maskSegments[$_index] != '*') $_segment += $maskSegments[$_index];

        return implode('.', $versionSegments);
    }

    /**
     * Check if module exists
     * @return boolean
     */
    public function exists()
    {
        // Decide by existance of config.xml file
        return Mtool_Codegen_Filesystem::exists($this->_moduleConfigsDir . DIRECTORY_SEPARATOR . 'config.xml');
    }

    /**
     * Get module dir path
     * @return string
     */
    public function getDir()
    {
        return $this->_moduleDir;
    }

    /**
     * Get path to config file
     *
     * @param string $file with .xml
     * @return string
     */
    public function getConfigPath($file)
    {
        return $this->_moduleConfigsDir . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * Get module name in format
     * Company_Module
     *
     * @return string
     */
    public function getName()
    {
        return "{$this->_companyName}_{$this->_moduleName}";
    }

    /**
     * Get company name
     * @return string
     */
    public function getCompanyName()
    {
        return $this->_companyName;
    }

    /**
     * Get module name
     * @return string
     */
    public function getModuleName()
    {
        return $this->_moduleName;
    }

    /**
     * Find file through modules
     *
     * @param string $search
     * @return RegexIterator
     */
    public function findThroughModules($search)
    {
        return $this->_mage->findInCode($search);
    }

    /**
     * Get params to substitude in templates
     *
     * @return array
     */
    public function getTemplateParams()
    {
        return $this->_templateParams;
    }

}
