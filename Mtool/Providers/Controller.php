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
class Mtool_Providers_Controller extends Mtool_Providers_Entity
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
     * @param string $controllerPath in format of area/controller_path (Ex: frontend/help or adminhtml/manageHelp)
     */
    public function create($targetModule = null, $controllerPath = null)
    {
        //check for correct area
        list($area, $entityName) = explode('/', $controllerPath);
        if (!in_array($area, array('adminhtml', 'admin', 'frontend'))) {
            $this->_answer('Incorrent area. Please use adminhtml or frontend');
            return;
        }

        //create controller file
        $this->_createEntity(new Mtool_Codegen_Entity_Controller(), 'controller', $targetModule, $controllerPath);
    }
}
