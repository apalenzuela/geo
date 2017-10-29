<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @class Answer
 * @author Alain Palenzuela
 * @version 1.5
 *
 ********/
class Answer
{
	private $header = '<?xml version="1.0" encoding="UTF-8" ?>';

	// -------------------------------------
	// Attributes
	// -------------------------------------
	private $attr	= array();
	private $value	= '';
	private $tag	= '';
	private $is_top = FALSE;

	// -------------------------------------
	
	/**
	 * Function: __construct
	 *
	 * This function accept the iniail parameters
	 * for the class
	 *
	 * @access public
	 * @param  string tag name
	 * @param  mixed
	 * @param  array attr
	 * @return none
	 ****/
	public function __construct($_tag = 'node', $_value = '', $_attr = array(), $_is_top = FALSE)
	{
		libxml_use_internal_errors(TRUE);

		// -------------------------------------
		// Check if the parameters are passed 
		// as an array
		// -------------------------------------
		if(is_array($_tag))
		{
			$_is_top = isset($_tag['is_top']) ? $_tag['is_top'] : FALSE;
			$_value  = isset($_tag['value'])  ? $_tag['value']  : '';
			$_attr   = isset($_tag['attr'])   ? $_tag['attr']   : array();
			$_tag    = isset($_tag['tag'])    ? $_tag['tag']    : 'node';
		}
		
		// -------------------------------------
		// Check if the parameter passed is an
		// XML string
		// -------------------------------------
		$xml = @simplexml_load_string(html_entity_decode($_tag), 'SimpleXMLElement', LIBXML_NOCDATA);
	
		if(is_object($_tag) && get_class($xml) == 'SimpleXMLElement')
		{
			$this->tag 	= '_is_xml';
			$this->value	= $_tag;
			$this->is_top	= FALSE;
		}

		// ------------------------------------
		// Parameters are passed individually
		// ------------------------------------
		if(empty($_tag))
		{
			throw new Exception('Exception Libray / Class '.get_class($this).' TagName can not be empty');
		}
		else
		{
			$this->tag = $_tag;
		}

		if(!empty($_value))
		{
			$this->value = $_value;
		}
		else if($_value == '0')
		{
			$this->value = '0';
		} 

		if(is_array($_attr) && count($_attr))
		{
			foreach($_attr as $attr_key => $attr_value)
			{
				$this->setAttr($attr_key, $attr_value);
			}
		}

		$this->is_top = (gettype($_is_top) == 'boolean') ? $_is_top : FALSE;

		log_message('DEBUG', "Class ".get_class($this)." Initialized");
	}

	// -------------------------------------

	/**
	 * Function: asXML
	 *
	 * this function return the values on the class attributes
	 * as XML structure into an string
	 *
	 * @access public 
	 * @param  none
	 * @return string
	 ***********/
	public function asXML()
	{
		$str_xml = '';

		// -------------------------------------------
		// For VALUE
		// -------------------------------------------
		if(is_array($this->value))
		{
			foreach($this->value as $child)
			{
				$str_xml .= (is_object($child) && get_class($child) == get_class($this))
					 ? $child->asXML()
					 : $child;
			}
		}
		else if(is_object($this->value) && get_class($this->value) == get_class($this))
		{
			$str_xml = $this->value->asXML();
		}
		else if($this->tag == "_is_xml" && is_object($this->value) && get_class($this->value) == 'SimpleXMLElement')
		{
			return $this->value;
		}
		else
		{
			$str_xml = xml_convert($this->value);
		}

		// -----------------------------------------
		// For ATTR
		// -----------------------------------------
		$arrAttr = array();
		if(is_array($this->attr) && count($this->attr) > 0)
		{
			foreach($this->attr as $key => $value)
			{
				$value = xml_convert($value);
				$arrAttr[] = "{$key}='{$value}'";
			}
		}

		$strAttr = '';
		if(count($arrAttr) > 0)
		{
			$strAttr = ' '.implode(' ', $arrAttr);
		}

		// -----------------------------------------
		// Prepare XML String
		// -----------------------------------------
		$initial_xml = "<{$this->tag}{$strAttr}>{$str_xml}</{$this->tag}>";
			
		return ($this->is_top ? $this->header : '').$initial_xml;
	}

	// -------------------------------------

	/**
	 * Function: asJSON
	 *
	 * this function return the values on the class attributes
	 * as JSON structure into an string, based on asXML function
	 *
	 * @access public
	 * @param  none
	 * @return string
	 **********/
	public function asJSON($default = FALSE)
	{
		$xmlNode   = @simplexml_load_string($this->asXML($default));
		$arrayData = $this->xmlToArray($xmlNode);

		return json_encode($arrayData);
	}

	// -------------------------------------

	/**
	 * Function: convert_to_XML
	 *
	 * this function convert the json parameter into
	 * XML structure
	 *
	 * @see http://vrushank.in/json-to-xml-conversion-php/
	 * @access public
	 * @param  string json
	 * @return string xml
	 ************/
	public function convert_to_XML($json = '')
	{
		$xml = '';
	
		try
		{
			$array = json_decode($json, TRUE);

			if(isset($array['api']))
			{
				$xml = $this->arrayToXml($array['api'], '<api></api>');
			}
		}
		catch(Exception $e)
		{
			return FALSE;
		}

		return $xml;
	}

	// -------------------------------------

	/**
	 * Function: arrayToXml
	 *
	 * this function convert array to XML
	 *
	 * @see http://vrushank.in/json-to-xml-conversion-php/
	 * @access private
	 * @param  array
	 * @param  xml node
	 * @return xml
	 *****/
	private function arrayToXml($array, $rootElement = null, $xml = null)
	{
		$_xml = $xml;
		
		if($_xml === null)
		{
			$_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root></root>');
		}

		foreach($array as $k => $v)
		{
			if(is_array($v))
			{
				$this->arrayToXml($v, $k, $_xml->addChild($k));
			}
			else
			{
				$_xml->addChild($k, $v);
			}
		}

		return $_xml->asXML();
	}

	// -------------------------------------

	/**
	 * Function: showHeader
	 *
	 * @access public
	 * @param  boolean
	 * @return none
	 ************/
	public function showHeader($showHeader = FALSE)
	{
		$this->is_top = (gettype($showHeader) == 'boolean') ? $showHeader : FALSE;
	}

	// -------------------------------------

	/**
	 * Function: setTag
	 *
	 * @access public
	 * @param  string
	 * @return none
	 *******************/
	public function setTag($_tag = '')
	{
		$this->tag = empty($_tag) ? $this->tag : $_tag;
	}

	// -------------------------------------

	/**
	 * Function: getTag
	 *
	 * @access public
	 * @param  none
	 * @return string
	 ************/
	public function getTag()
	{
		return $this->tag;
	}

	// -------------------------------------

	/**
	 * Function: setValue
	 *
	 * @access public
	 * @param  mixed
	 * @return none
	 ********/
	public function setValue($_value)
	{
		$this->value = $_value;
	}

	// -------------------------------------

	/**
	 * Function: addValue
	 *
	 * @access public
	 * @param  mixed
	 * @return none
	 *******/
	public function addValue($_value)
	{
		switch(gettype($this->value))
		{
			case 'array':
				switch(gettype($_value))
				{
					case 'array':
						$this->value = array_merge($this->value, $_value);
						break;
					case 'object':
						$arrTemp = array($_value);
						$this->value = array_merge($this->value, $arrTemp);
						break;
					case 'string':
						$this->value[] = new Answer('node', $_value);
						break;
				}
				break;
			case 'object':
				switch(gettype($_value))
				{
					case 'array':
						$arrTemp = array($this->value);
						$this->value = array_merge($arrTemp, $_value);
						break;
					case 'object':
						switch(get_class($_value))
						{
							case 'Answer':
								$arrTemp = array($this->value, $_value);
								break;
							case 'SimpleXMLElement':
								$this->value = $_value;
								break;
						}
						break;
					case 'string':
						$arrTemp = array($this->value);
						$arrTemp[] = new Answer('node', $_value);
						$this->value = $arrTemp;
						break;
				}
				break;
			case 'string':
				switch(gettype($_value))
				{
					case 'array':
						if(!empty($this->value))
						{
							$_value[] = new Answer('node', $this->value);
						}
						$this->value = $_value;
						break;
					case 'object':
						$tmpValues = $this->value;
						$this->value = array();
						if(!empty($tmpValues))
						{
							$this->value[] = new Answer('node', $tmpValues);
						}
						$this->values[] = $_value;
						break;
					case 'string':
						$tmpValues = $this->value;
						$this->value = array();
						
						if(!empty($tmpValues))
						{
							$this->value[] = new Answer('node', $tmpValues);
						}
						
						if(!empty($_value))
						{
							$this->value[] = new Answer('node', $_value);
						}
						break;
				}
				break;
		}


	}

	// -------------------------------------

	/**
	 * Function: getValue
	 *
	 * @access public
	 * @param  string 
	 * @return mixed
	 *
	 *******/
	public function getValue($idx = '')
	{
		if(empty($idx))
		{
			return empty($this->value) ? FALSE : $this->value;
		}
		else
		{
			if(is_array($this->value) && in_array($idx, $this->value))
			{
				return $this->value[$idx];
			}

			return FALSE;
		}
	}

	// -------------------------------------

	/**
	 * Function: getTagValue
	 *
	 * this function return value by the tag
	 *
	 * @access public
	 * @param  string
	 * @return mixed
	 *
	 **********/
	public function getTagValue($_tag)
	{
		if($this->tag == $_tag)
		{
			return $this->value;
		}
		else if(is_array($this->value))
		{
			foreach($this->value as $item)
			{
				if(gettype($item) == 'object' && get_class($item) == 'Answer' && $item->getTag() == $_tag)
				{
					return $item->getValue();
				}
				else
				{
					$item->getTagValue($_tag);
				}
			}
		}
		else if(gettype($this->value) == 'object' && get_class($this->value) == 'Answer' && $this->value->getTag() == $_tag)
		{
			return $this->value->getValue();
		}
		else if($this->tag == "_is_xml" && is_object($this->value) && get_class($this->value) == "SimpleXMLElement")
		{
			return $this->value;
		}
	}

	// -------------------------------------

	/**
	 * Function: removeValue
	 *
 	 * @access public
	 * @param  string
	 * @return none
	 **********/
	public function removeValue($idx = '')
	{
		$_result = TRUE;
		if(empty($idx))
		{
			$this->value = '';
		}
		else
		{
			if(is_array($this->value) && in_array($idx, $this->value))
			{
				unset($this->value[$idx]);
			}
			else
			{
				$_result = FALSE;
			}
		}

		return $_result;
	}

	// -------------------------------------

	/**
	 * Function: xmlToArray
	 *
	 * this function convert XML struc to an array
	 *
	 * @see http://outlandish.com/blog/xml-to-json/
	 * @access public
	 * @param  string
	 * @param  array
	 * @return array
	 ********************/
	public function xmlToArray($xml = '', $options = array())
	{
    		$defaults = array(
        		'namespaceSeparator'	=> ':',			// you may want this to be something other than a colon
        		'attributePrefix' 	=> '@',			// to distinguish between attributes and nodes with the same name
        		'alwaysArray' 		=> array(),		// array of xml tag names which should always become arrays
        		'autoArray' 		=> TRUE,		// only create arrays for tags which appear more than once
        		'textContent' 		=> '$',			// key used for the text content of elements
        		'autoText' 		=> TRUE,		// skip textContent key if node has no attributes or child nodes
        		'keySearch' 		=> FALSE,		// optional search and replace on tag and attribute names
        		'keyReplace' 		=> FALSE 		// replace values for above search values (as passed to str_replace())
    		);

    		$options	= array_merge($defaults, $options);
    		$namespaces 	= $xml->getDocNamespaces();
    		$namespaces[''] = null; 					//add base (empty) namespace

    		// ---------------------------------------------
    		// Get attributes from all namespaces
    		// ---------------------------------------------
    		$attributesArray = array();
    		foreach ($namespaces as $prefix => $namespace) 
    		{
        		foreach ($xml->attributes($namespace) as $attributeName => $attribute) 
        		{
        			// ---------------------------------------------
            			// Replace characters in attribute name
            			// ---------------------------------------------
            			if ($options['keySearch'])
            			{ 
            				$attributeName = str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
            			}
	
            			$attributeKey = $options['attributePrefix']. 
            				($prefix ? $prefix . $options['namespaceSeparator'] : '').
            				$attributeName;

            			$attributesArray[$attributeKey] = (string)$attribute;
        		}
    		}

    		// ---------------------------------------------
    		// Get child nodes from all namespaces
    		// ---------------------------------------------
    		$tagsArray = array();
    		foreach ($namespaces as $prefix => $namespace) 
    		{
        		foreach ($xml->children($namespace) as $childXml) 
        		{
        			// --------------------------------------------
            			// Recurse into child nodes
            			// --------------------------------------------
            			$childArray = $this->xmlToArray($childXml, $options);
            			list($childTagName, $childProperties) = each($childArray);
 
            			// --------------------------------------------
            			// Replace characters in tag name
            			// --------------------------------------------
            			if ($options['keySearch']) 
            			{
            				$childTagName = str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
            			}

            			// --------------------------------------------
            			// Add namespace prefix, if any
            			// --------------------------------------------
            			if ($prefix) 
            			{
            				$childTagName = $prefix.$options['namespaceSeparator'].$childTagName;
            			}
 
            			if (!isset($tagsArray[$childTagName])) 
            			{
            				// --------------------------------------------
                			// Only entry with this key
                			// test if tags of this type should always
                			// be arrays, no matter the element count
                			// ---------------------------------------------
                			$tagsArray[$childTagName] = in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
                        			? array($childProperties) 
                        			: $childProperties;

	            		} 
	            		elseif (is_array($tagsArray[$childTagName]) 
				&& array_keys($tagsArray[$childTagName]) === range(0, count($tagsArray[$childTagName]) - 1)) 	
	            		{
	            			// ----------------------------------------------
	            			// Key already exists and is integer indexed array
	            			// ----------------------------------------------
	                		$tagsArray[$childTagName][] = $childProperties;
            			} 
            			else 
            			{
            				// -----------------------------------------------
                			// Key exists so convert to integer indexed array 
                			// with previous value in position 0
                			// -----------------------------------------------
                			$tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
            			}
        		}
    		}

    		// --------------------------------------------
    		// Get text content of node
    		// --------------------------------------------
    		$textContentArray  = array();
    		$plainText         = trim((string)$xml);

    		if ($plainText !== '')
    		{
    			$textContentArray[$options['textContent']] = $plainText;
    		}
 
 		// ---------------------------------------------
 		// Stick it all together
 		// ---------------------------------------------
    		$propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
        		? array_merge($attributesArray, $tagsArray, $textContentArray) 
        		: $plainText;
 
 		// ----------------------------------------------
        	// Return node as array
        	// ----------------------------------------------
    		return array(
        		$xml->getName() => $propertiesArray
    		);
	}

	// -------------------------------------

	/**
	 * Function: setAttr
	 *
	 * @access public
	 * @param  string
	 * @param  mixed
	 * @return none
	 **********/
	public function setAttr($attrName = '', $attrValue = '')
	{
		if(is_array($attrName))
		{
			$this->attr = array_merge($this->attr, $attrName);
		}
		else if(!empty($attrName) && (!empty($attrValue) || $attrValue == '0'))
		{
			$this->attr[$attrName] = $attrValue;
		}
	}

	// -------------------------------------

	/**
	 * Function: getAttr
	 *
	 * @access public
	 * @param  string
	 * @return mixed
	 ********/
	public function getAttr($attrIdx = '')
	{
		if(!empty($attrIdx) && array_key_exists($attrIdx, $this->attr))
		{
			return $this->attr[$attrIdx];
		}

		return FALSE;
	}	

	// -------------------------------------

	/**
	 * Function: removeAttr
	 *
	 * @access public
	 * @param  string
	 * @return none
	 ********/
	public function removeAttr($attrName = '')
	{
		if(!empty($attrName) && array_key_exists($attrName, $this->attr))
		{
			unset($this->attr[$attrName]);
		}
	}

	// -------------------------------------
	// -------------------------------------
	// -------------------------------------
	// -------------------------------------
}

/* End of file Answer.php */
/* Location: application/libraries/Answer.php */
