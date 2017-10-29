<?php defined('BASEPATH') OR exit('No direct script access allowed');

/** ------------------------------------
 *
 * @author Ing Jose Alain Palenzuela
 * @version 1.0
 * @class Params
 *
**/
class Params
{
	const PRM_COMMAND 	= 'command';
	const PRM_IP		= 'ip';
	const PRM_ZIP		= 'zip';
	const PRM_OUTPUT 	= 'output';
	const PRM_COUNTRY	= 'country';
	const PRM_AGENT         = 'agent';
	const PRM_REQUEST	= 'request';
	const PRM_IMPORT 	= 'import';

	private $attr = array();

	private $defaults = array(
		self::PRM_COMMAND	=> '',
		self::PRM_IP		=> '',
		self::PRM_ZIP		=> '',
		self::PRM_COUNTRY	=> '',
		self::PRM_AGENT 	=> '',
		self::PRM_REQUEST	=> '',
		self::PRM_IMPORT	=> '',
		self::PRM_OUTPUT	=> DFLT_OUTPUT,
	);

	// -------------------------------------------------------------

	/**
	 * Function: __constructor
	 *
	 * Constructor of the class, if some values
	 * are passed, then the entries are initialized
	 *
	 * @access public
	 * @param  array
	 * @return none
	**/
	public function __construct($map = array())
	{
		if(is_array($map))
		{
			$CI =& get_instance();
			$commands = $CI->config->item('commands');
			
			$keys = $this->keys();
			foreach($map as $idx => $item)
			{
				if(in_array(strtolower($idx), $keys))
				{
					$this->set($idx, $item);
				}
			}

			$this->set(self::PRM_COMMAND, $this->get_command());			
		}
	}

	// ---------------------------------------------

	/**
	 * Function: get_command
	 *
	 * this command find (if exists) the command
	 * passed as a parameter through the URL
	 *
	 * @access public
	 * @param  none
	 * @return string|false
	**/
	public function get_command()
	{
		if(!isset($this->attr[self::PRM_COMMAND]))
		{
			$CI =& get_instance();
			$commands = $CI->config->item('commands');

			foreach($this->keys() as $key)
			{
				if(in_array(strtolower($key), $commands))
				{
					$this->attr[self::PRM_COMMAND] = $key;
				}
			}
		}

		return isset($this->attr[self::PRM_COMMAND]) ? $this->attr[self::PRM_COMMAND] : NULL;
	}
	
	// ---------------------------------------------

	/**
	 * Function: get_params
	 *
	 * this function return the array with all 
	 * params with the defaults values if the function
	 * param is set to FALSE, otherwise, only the passed 
	 * parameters
	 *
	 * @access public
	 * @param  boolean only_passed
	 * @return array
	**/ 
	public function get_params($passed_only = TRUE)
	{
		$result = array();

		if($passed_only == FALSE)
		{
			foreach($this->defaults as $idx => $value)
			{
				$result[$idx] = $value;
			}
		}

		foreach($this->attr as $idx => $value)
		{
			if(substr($idx, 0, 2) != 'h_')
			{
				$result[$idx] = $value;
			}
		}
		
		return $result;
	}
	
	// ---------------------------------------------
	
	/**
	 * Function: get
	 *
	 * this function recover the value is the param
	 * exists on the passed values, if not search into
	 * the array with the defaults values, if not exists
	 * on any of the previous array return FALSE
	 *
	 * @access public 
	 * @param  string key
	 * @return mixed
	**/ 
	public function get($idx = '')
	{
		if(isset($this->attr["h_".strtolower($idx)]))
		{
			return $this->attr["h_".strtolower($idx)];
		}
		else	
		if(isset($this->attr[strtolower($idx)]))
		{
			return $this->attr[strtolower($idx)];
		}
		else
		if(isset($this->defaults[strtolower($idx)]))
		{
			return $this->defaults[strtolower($idx)];
		}

		return NULL;
	}

	// ---------------------------------------------

	/**
	 * Function: set
	 *
	 * This function set the value for an entry on the 
	 * parameters array
	 *
	 * @access public
	 * @param  string key
	 * @param  string value
	 * @return none
	**/ 
	public function set($idx = '', $value = '')
	{
		if(empty($idx))
		{
			// Nothing ...
		}
		else
		{
			$CI =& get_instance();
			$CI->load->helper('htmlpurifier_helper');

			$idx   = html_purify($idx);
			$value = html_purify($value);

			$this->attr[strtolower($idx)] = $value;
		}
	}

	// ---------------------------------------------

	/**
	 * Function: query_params
	 *
	 * This function return the parameters if was 
	 * passed on the URL
	 *
	 * @access public
	 * @param  none
	 * @return string|boolean
	**/
	public function query_params()
	{
	
	}

	// ---------------------------------------------

	/**
	 * Function: keys
	 *
	 * this function return an array with all 
	 * posibles valid parameters
	 *
	 * @access public
	 * @param  none
	 * @return array
	**/
	public function keys()
	{
		$default_keys = array_keys($this->defaults);
		$attr_keys    = array_keys($this->attr);

		foreach($attr_keys as $idx => $value)
		{
			if(substr($value, 0, 2) == 'h_')
			{
				unset($attr_keys[$idx]);
			}
		}

		return array_merge($default_keys, $attr_keys);
	}

	// ---------------------------------------------
	// Magic functions
	// ---------------------------------------------

	/**
	 * Function: __get (magic function)
	 *
	 * this function return the value for any of
	 * the parameters if exists on one of the internal
	 * arrays (defaults or attr passed)
	 *
	 * @access public
	 * @param  string index
	 * @return mixed
	**/
	public function __get($idx = '')
	{
		return $this->get($idx);
	}

	// ---------------------------------------------

	/**
	 * Function: __set (magic function)
	 *
	 * this function assign a new value to attr array
	 * with it's key
	 * 
	 * @access public
	 * @param  string index
	 * @param  mixed  value
	 * @return none
	**/
	public function __set($idx = '', $value = '')
	{
		$this->set($idx, $value);
	}

	// ---------------------------------------------
}

/* End of file Params.php */
/* Location: application/libraries/Params.php */
