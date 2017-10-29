<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter - GBTI Solutions, Inc.
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		BARD Dev Team
 * @copyright	Copyright (c) 2012 - 2012, GBTI Solutions, Inc.
 * @since		Version 1.0
 * @filesource
 */

// ---------------------------------------------------------------------
// ---------------------------------------------------------------------

/**
 * Function: get_url_map
 *
 * The function return all parameters from current URL
 *
 * @access public
 * @param  none
 * @return string
 */
if( ! function_exists('get_url_map'))
{
	function get_url_map()
	{
		$CI =& get_instance();

		$params = array();
		$temp_array = $CI->uri->uri_to_assoc(URL_SEGMENTS);
		
		foreach ($temp_array as $key => $item) 
		{
			$params[$CI->security->xss_clean($key)] = $CI->security->xss_clean($item);
		}

		return $params;
	}
}

// ---------------------------------------------------------------------
// ---------------------------------------------------------------------

/**
 *  Function: log_debug()
 *
 *  This function save on a TEXT file, named
 *  by IP address and date, the information
 *  about different status trough the entire 
 *  API
 *
 * @access public 
 * @param  integer
 * @param  optional array
 * @return string
 */
if( ! function_exists('log_debug'))
{
	function log_debug( $msg = '', $array = array())
	{
        if(isset($msg) && isset($array))
        {
            $CI =& get_instance();
            $user_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOW_IP';
            $date  = date(STR_DATEONLY_FORMAT);

            // echo ((DBG_STATUS == TRUE) ? "Status: TRUE <br />" : "Status: FALSE <br />");
            // echo (($CI->session->userdata('devices_observed') == TRUE) ? "Device: TRUE <br />" : "Device: FALSE <br />");
            // echo "-------------------------------- <br />";

            if(DBG_STATUS == TRUE || $CI->session->userdata('devices_observed'))
            {
                $found = FALSE;

                // -------------------------------------------------------------
                // Verify if the current device is on the list of debugged 
                // devices.
                // -------------------------------------------------------------
                if(DBG_STATUS == TRUE)
                {
                    $dbg_ip_ranges = unserialize(DBG_IP_RANGE);
                    foreach($dbg_ip_ranges as $ip_range)
                    {
                        $found = (strpos($user_ip, $ip_range) === FALSE) ? $found : TRUE;
                    }
                }

                // -------------------------------------------------------------
                // If in any ways the found variable is TRUE, then we save
                // the values on the log.
                // -------------------------------------------------------------
                if($found == TRUE)
                {
                    // -------------------------------------------------------------
                    // Improving log_debug
                    // -------------------------------------------------------------
                    $debug = debug_backtrace(false);                                                    

                    $type  = '';                                                                               
                    $file_path = isset($debug[0]['file']) ? $debug[0]['file'] : '';                     
                    if(!empty($file_path))                                                               
                    {
                        if(!(strpos($file_path, 'controllers') === FALSE)) {  $type = '__CTRL__'; }
                        if(!(strpos($file_path, 'core') === FALSE))        {  $type = '__CORE__'; }
                        if(!(strpos($file_path, 'helper') === FALSE))      {  $type = '__HELP__'; }
                        if(!(strpos($file_path, 'libraries') === FALSE))   {  $type = '__LBRY__'; }
                        if(!(strpos($file_path, 'models') === FALSE))      {  $type = '__MDEL__'; }
                        if(!(strpos($file_path, 'views') === FALSE))       {  $type = '__VIEW__'; }
                    }

                    $class = isset($debug[1]['class']) ? $debug[1]['class'] : '';
                    $func  = isset($debug[1]['function']) ? $debug[1]['function'] : '';
                    $line  = isset($debug[0]['line']) ? $debug[0]['line'] : ''; 

                    // ------------------------------------------------------------
                    // Improving log_debug
                    // ------------------------------------------------------------

                    if($hd = get_handler($user_ip, $date))
                    {
                        $datetime = date(STR_DATETIME_FORMAT);
                        $str_array = count($array) > 0 ? ' - '.json_encode($array) : '';
                        fwrite($hd, "DBG - {$datetime} {$type}[{$class}::{$func} ($line)] => ".$msg.' '.$str_array."\n");                 
                    }
                }
            }
        }
	}
}

// ---------------------------------------------------------------------
// ---------------------------------------------------------------------

/** 
 *  Function get_handler()
 *
 * This function create/open the text file
 * where we record all transaction for each 
 * call.
 *
 * @access public 
 * @param  integer
 * @return string
 */
if( ! function_exists("get_handler"))
{
	function get_handler( $user_ip, $date, $folder_path = DBG_PATH )
	{
		$user_addr = $user_ip;

		$app_path  = pathinfo($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$_SERVER['SCRIPT_NAME']);
		$PATH      = (isset($app_path['dirname'])) ? $app_path['dirname'].'/' : './';
		$filename  = $PATH.$folder_path.DIRECTORY_SEPARATOR."dbg_{$user_addr}_{$date}.php";

		$handler   = null;
		if(file_exists($filename))
		{
			if($handler = fopen($filename, "a+"))
            {

            }
		}
		else
		{
			if($handler = fopen($filename, "a+"))
            {
		        fwrite($handler, "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?> \n");
			    fwrite($handler, "\n");
            }
		}

		return $handler;
	}
}

// ---------------------------------------------------------------------
// ---------------------------------------------------------------------

/**
 * Function: TriggerException
 *
 * This function Trigger an Exception for
 * specific parameters
 *
 * @access public 
 * @param  integer
 * @return Exception
 */
if( ! function_exists('TriggerException'))
{
	function TriggerException( $code = CODE_0, $file = '', $line = '', $str_added = '')
	{
		$CI =& get_instance();
		$msg = $CI->config->item('msg');
		
		$message = $msg[$code].(empty($str_added) ? '' : ' - '.$str_added);
		$message = mb_check_encoding($message, 'UTF-8') ? $message : utf8_encode($message);
		
		$strException = json_encode(array(
			EX_CODE	=> $code,
			EX_MSG	=> $message
		));

		log_debug("Exception File: ".$file."(".$line.") => ".$strException);
		throw new Exception($strException);
	}
}

// ---------------------------------------------------------------------
// ---------------------------------------------------------------------

/**
 * Parses a user agent string into its important parts
 *
 * @author Jesse G. Donat <donatj@gmail.com>
 * @link https://github.com/donatj/PhpUserAgent
 * @link http://donatstudios.com/PHP-Parser-HTTP_USER_AGENT
 * @param string|null $u_agent User agent string to parse or null. Uses $_SERVER['HTTP_USER_AGENT'] on NULL
 * @throws InvalidArgumentException on not having a proper user agent to parse.
 * @return string[] an array with browser, version and platform keys
 */
if( ! function_exists('parse_user_agent'))
{
function parse_user_agent( $u_agent = null ) 
{
	if( is_null($u_agent)) 
	{
		if(isset($_SERVER['HTTP_USER_AGENT'])) 
		{
			$u_agent = $_SERVER['HTTP_USER_AGENT'];
		} 
		else 
		{
			TriggerException(CODE_10, __FILE__, __LINE__);
			// throw new \InvalidArgumentException('parse_user_agent requires a user agent');
		}
	}

	$platform = null;
	$browser  = null;
	$version  = null;

	$empty = array( 
		'platform' 	=> $platform, 
		'browser' 	=> $browser, 
		'version' 	=> $version );

	if(!$u_agent)
	{
		return $empty;
	}	

	if( preg_match('/\((.*?)\)/im', $u_agent, $parent_matches) ) 
	{
		preg_match_all('/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|iPod|Linux|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|X11|(New\ )?Nintendo\ (WiiU?|3?DS)|Xbox(\ One)?)
				(?:\ [^;]*)?
				(?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);
		
		$priority = array( 'Xbox One', 'Xbox', 'Windows Phone', 'Tizen', 'Android', 'CrOS', 'X11' );
		
		$result['platform'] = array_unique($result['platform']);
		
		if(count($result['platform']) > 1) 
		{
			if($keys = array_intersect($priority, $result['platform'])) 
			{
				$platform = reset($keys);
			} 
			else 
			{
				$platform = $result['platform'][0];
			}
		} 
		elseif(isset($result['platform'][0])) 
		{
			$platform = $result['platform'][0];
		}
	}
	if( $platform == 'linux-gnu' || $platform == 'X11' ) 
	{
		$platform = 'Linux';
	} 
	elseif( $platform == 'CrOS' ) 
	{
		$platform = 'Chrome OS';
	}

	preg_match_all('%(?P<browser>Camino|Kindle(\ Fire)?|Firefox|Iceweasel|Safari|MSIE|Trident|AppleWebKit|TizenBrowser|Chrome|
				Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|CriOS|UCBrowser|
				Baiduspider|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|
				Valve\ Steam\ Tenfoot|
				NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
				(?:\)?;?)
				(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',

	$u_agent, $result, PREG_PATTERN_ORDER);
	
	// If nothing matched, return null (to avoid undefined index errors)
	if( !isset($result['browser'][0]) || !isset($result['version'][0]) ) 
	{
		if( preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?%ix', $u_agent, $result) ) 
		{
			return array( 
				'platform' => $platform ?: null, 
				'browser' => $result['browser'], 
				'version' => isset($result['version']) 
					? $result['version'] ?: null : null );
		}

		return $empty;
	}

	if( preg_match('/rv:(?P<version>[0-9A-Z.]+)/si', $u_agent, $rv_result) ) 
	{
		$rv_result = $rv_result['version'];
	}

	$browser = $result['browser'][0];
	$version = $result['version'][0];
	$lowerBrowser = array_map('strtolower', $result['browser']);

	$find = function ( $search, &$key, &$value = null ) use ( $lowerBrowser ) 
	{
		$search = (array)$search;
		foreach( $search as $val ) 
		{
			$xkey = array_search(strtolower($val), $lowerBrowser);
			if( $xkey !== false ) {
				$value = $val;
				$key   = $xkey;
				return true;
			}
		}
		return false;
	};

	$key = 0;
	$val = '';
	if( $browser == 'Iceweasel' ) 
	{
		$browser = 'Firefox';
	} 
	elseif( $find('Playstation Vita', $key) ) 
	{
		$platform = 'PlayStation Vita';
		$browser  = 'Browser';
	} 
	elseif( $find(array( 'Kindle Fire', 'Silk' ), $key, $val) ) 
	{
		$browser  = $val == 'Silk' ? 'Silk' : 'Kindle';
		$platform = 'Kindle Fire';
		if( !($version = $result['version'][$key]) || !is_numeric($version[0]) ) 
		{
			$version = $result['version'][array_search('Version', $result['browser'])];
		}
	} 
	elseif( $find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS' ) 
	{
		$browser = 'NintendoBrowser';
		$version = $result['version'][$key];
	} 
	elseif( $find('Kindle', $key, $platform) ) 
	{
		$browser = $result['browser'][$key];
		$version = $result['version'][$key];
	} 
	elseif( $find('OPR', $key) ) 
	{
		$browser = 'Opera Next';
		$version = $result['version'][$key];
	} 
	elseif( $find('Opera', $key, $browser) ) 
	{
		$find('Version', $key);
		$version = $result['version'][$key];
	} 
	elseif( $find(array( 'IEMobile', 'Edge', 'Midori', 'Vivaldi', 'Valve Steam Tenfoot', 'Chrome' ), $key, $browser) ) 
	{
		$version = $result['version'][$key];
	} 
	elseif( $browser == 'MSIE' || ($rv_result && $find('Trident', $key)) ) 
	{
		$browser = 'MSIE';
		$version = $rv_result ?: $result['version'][$key];
	} 
	elseif( $find('UCBrowser', $key) ) 
	{
		$browser = 'UC Browser';
		$version = $result['version'][$key];
	} 
	elseif( $find('CriOS', $key) ) 
	{
		$browser = 'Chrome';
		$version = $result['version'][$key];
	} 
	elseif( $browser == 'AppleWebKit' ) 
	{
		if( $platform == 'Android' && !($key = 0) ) 
		{
			$browser = 'Android Browser';
		} 
		elseif( strpos($platform, 'BB') === 0 ) 
		{
			$browser  = 'BlackBerry Browser';
			$platform = 'BlackBerry';
		} 
		elseif( $platform == 'BlackBerry' || $platform == 'PlayBook' ) 
		{
			$browser = 'BlackBerry Browser';
		} 
		else 
		{
			$find('Safari', $key, $browser) || $find('TizenBrowser', $key, $browser);
		}

		$find('Version', $key);
		$version = $result['version'][$key];
	} 
	elseif( $pKey = preg_grep('/playstation \d/i', array_map('strtolower', $result['browser'])) ) 
	{
		$pKey = reset($pKey);
		$platform = 'PlayStation ' . preg_replace('/[^\d]/i', '', $pKey);
		$browser  = 'NetFront';
	}

	return array( 'platform' => $platform ?: null, 'browser' => $browser ?: null, 'version' => $version ?: null );
}
}
// ---------------------------------------------------------------------
// ---------------------------------------------------------------------



/* End of file general_helper.php */
/* Location: ./application/helpers/general_helper.php */
