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
 * Config writer
 *
 * @category   Mtool
 * @package    Mtool_Codegen
 * @author     Daniel Kocherga <dan@oggettoweb.com>
 */
class Mtool_Codegen_Config
{
    /**
     * Xml handle
     * @var SimpleXMLElement
     */
    protected $_xml;

    /**
     * Config path
     * @var string
     */
    protected $_path;

    /**
     * Load config
     *
     * @param string $filePath
     *
     * @throws Mtool_Codegen_Exception_Config
     */
    public function __construct($filePath)
    {
        libxml_use_internal_errors(true);
        if(!Mtool_Codegen_Filesystem::exists($filePath))
            throw new Mtool_Codegen_Exception_Config("Config file does not exist: {$filePath}");

        $this->_xml = simplexml_load_file($filePath);
        if($this->_xml === false)
        {
            $message = "Cannot load config file: {$filePath}";
            foreach(libxml_get_errors() as $_error)
                $message .= "; {$_error->message}";
            throw new Mtool_Codegen_Exception_Config($message);
        }

        $this->_path = $filePath;
    }

    /**
     * Get xml config
     *
     * @return SimpleXMLElement
     */
    public function getXml()
    {
        return $this->_xml;
    }

    /**
     * Set config value
     *
     * @param string $path separated by slash (/)
     * @param string $value
     * @param array $attributes
     *
     * @return Mtool_Codegen_Config
     */
    public function set($path, $value, $attributes = array())
    {
        $segments = explode('/', $path);
        $node = $this->_xml;
        foreach($segments as $_key => $_segment) {
            if(!$node->$_segment->getName()) {
                $node->addChild($_segment);
            }

            if($_key == count($segments) - 1) {
                $node->$_segment = $value;
                foreach ($attributes as $_attribute => $_value) {
                    $node->$_segment->addAttribute($_attribute, $_value);
                }
            }

            $node = $node->$_segment;
        }

        return $this->save();
    }

    /**
     * Format xml with indents and line breaks
     *
     * @return string
     * @author Gary Malcolm
     */
    public function asPrettyXML()
    {
        $string = $this->_xml->asXML();

        // put each element on it's own line
        $string =preg_replace("/>\s*</",">\n<",$string);

        // each element to own array
        $xmlArray = explode("\n",$string);

        // holds indentation
        $currIndent = 0;

        $indent = "    ";
        // set xml element first by shifting of initial element
        $string = array_shift($xmlArray) . "\n";
        foreach($xmlArray as $element)
        {
            // find open only tags... add name to stack, and print to string
            // increment currIndent
            if (preg_match('/^<([\w])+[^>\/]*>$/U',$element))
            {
               $string .=  str_repeat($indent, $currIndent) . $element . "\n";
               $currIndent += 1;
            } // find standalone closures, decrement currindent, print to string
            elseif ( preg_match('/^<\/.+>$/',$element))
            {
               $currIndent -= 1;
               $string .=  str_repeat($indent, $currIndent) . $element . "\n";
            } // find open/closed tags on the same line print to string
            else
               $string .=  str_repeat($indent, $currIndent) . $element . "\n";
        }
        return $string;
    }

    /**
     * Read the config value
     *
     * @param string $path
     * @return string
     */
    public function get($path)
    {
        $node = $this->_xml;
        foreach(explode('/', $path) as $_segment)
            if($node->$_segment)
                $node = $node->$_segment;

        return (string) trim($node);
    }

    /**
     * Save xml data
     *
     * @return Mtool_Codegen_Config
     */
    public function save()
    {
        Mtool_Codegen_Filesystem::write($this->_path, $this->asPrettyXML());

        return $this;
    }
}
