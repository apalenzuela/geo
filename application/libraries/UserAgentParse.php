<?php defined('BASEPATH') OR exit("No direct script access allowed");

/**
 * @class UserAgentParse
 * @author Harald Hope, 
 * @site   http://techpatterns.com
 * @source http://techpatterns.com/downloads/php_browser_detection.php
 * @version 5.7.2
 * @date 2016-09-12
 * @copyright 2003-2016
**/
/**********************************************/
/**
 * Special thanks to alanjstr for cleaning up the code, 
 * especially on function get_item_version(), which he improved 
 * greatly. Also to Tapio Markula, for his initial inspiration 
 * of creating a useable php browser detector. Also to silver 
 * Harloe for his ideas about using associative arrays to 
 * both return and use as main return handler.
 *
 * This program is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 3 
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * Get the full text of the GPL here: http://www.gnu.org/licenses/gpl.txt
 *
 * Coding conventions:
 * http://cvs.sourceforge.net/viewcvs.py/phpbb/phpBB2/docs/codingstandards.htm?rev=1.3
 *
 * Requires PHP verson 4.2 or newer.
 *
 ********************/

class UserAgentParse
{
	private $user_agent 		= ""; 	
	private $script_time 		= 0;

	private $a_blink_data		= '';
	private $a_browser_math_number 	= '';
	private $a_full_assoc_data 	= '';
	private $a_full_data 		= '';
	private $a_khtml_data 		= '';
	private $a_mobile_data 		= '';
	private $a_moz_data 		= '';
	private $a_os_data 		= '';
	private $a_trident_data 	= '';
	private $a_unhandled_browser 	= '';
	private $a_webkit_data 		= '';
        private $a_engine_data          = '';
	private $b_dom_browser 		= false;
	private $b_os_test 		= true;
	private $b_mobile_test 		= true;
	private $b_safe_browser 	= false;
	private $b_success 		= false;/** boolean for if browser found in main test **/
	private $blink_type 		= '';
	private $blink_type_number 	= '';
	private $browser_name 		= '';
	private $browser_math_number 	= '';
	private $browser_temp 		= '';
	private $browser_working 	= '';
	private $browser_number 	= '';
	private $html_type 		= '';
	private $html_type_browser_nu 	= '';
	private $ie_version 		= '';
	private $layout_engine 		= '';
	private $layout_engine_nu 	= '';
	private $layout_engine_nu_full 	= '';
	private $khtml_type 		= '';
	private $khtml_type_number 	= '';
	private $mobile_test 		= '';
	private $moz_release_date 	= '';
	private $moz_rv 		= '';
	private $moz_rv_full 		= '';
	private $moz_type 		= '';
	private $moz_type_number 	= '';
	private $os_number 		= '';
	private $os_type 		= '';
	private $trident_type 		= '';
	private $trident_type_number 	= '';
	private $true_ie_number 	= '';
	private $ua_type 		= 'bot';/** default to bot since you never know with bots **/
	private $webkit_type 		= '';
	private $webkit_type_number 	= '';
	private $slice_increment	= 0;

	private $a_blink_types 		= array(
	    	'opr/', 	'vivaldi',	'chromium', 
	    	'chrome', 	'blink'
	);

	private $a_gecko_types 		= array( 
		'bonecho', 	'camino',	'conkeror', 
		'epiphany', 	'fennec', 	'firebird',
		'flock', 	'galeon', 	'iceape', 
		'icecat', 	'k-meleon', 	'minimo', 
		'multizilla', 	'phoenix',	'skyfire', 
		'songbird', 	'swiftfox', 	'seamonkey', 
    		'shadowfox', 	'shiretoko', 	'iceweasel',
    		'firefox', 		'minefield', 	'netscape6', 
    		'netscape', 	'rv' 
	);

	private $a_khtml_types = array( 
    		'konqueror', 	'khtml' 
	);

	private $a_trident_types = array( 
    		'ucbrowser', 	'ucweb', 	'msie' 
	);

	private $a_webkit_types = array( 
    		'arora', 	'bolt', 	'beamrise', 
    		'chromium', 	'puffin', 	'chrome',
    		'crios', 	'dooble', 	'epiphany', 
    		'gtklauncher', 	'icab', 	'konqueror', 
    		'maxthon',  	'midori',	'omniweb', 
    		'opera', 	'qupzilla', 	'rekonq', 
    		'rocketmelt', 	'samsungbrowser', 'silk', 
    		'uzbl',		'ucbrowser', 	'ucweb', 
    		'shiira', 	'sputnik', 	'steel', 
    		'teashark', 	'safari',  	'applewebkit',
    		'webos', 	'xxxterm',	'vivaldi', 
    		'yabrowser', 	'webkit' 
	);



	// [ Internal Definition ]-----------------------------

	private $a_browser_types = array(
		array('baidu', false, 'baidu', 'bot'),
		array('bingbot', false, 'bing', 'bot'),
        	array('bingpreview', false, 'bing', 'bot'),
        	array('msnbot', false, 'msn', 'bot'),
        	array('exabot', false, 'exabot', 'bot'),
        	array('googlebot', false, 'google', 'bot' ),
        	array('google web preview', false, 'googlewp', 'bot' ),
        	array('yandex', false, 'yandex', 'bot' ),
        	array('edge', true,  'edge', 'bro' ),
        	array('msie', true, 'ie', 'bro' ),
        	array('trident', true, 'ie', 'bro' ),
        	array('blink', true, 'blink', 'bro' ),
		array('opr/', true, 'blink', 'bro' ),
		array('vivaldi', true, 'blink', 'bro' ),
		array('webkit', true, 'webkit', 'bro' ),
		array('opera', true, 'op', 'bro' ),
		array('khtml', true, 'khtml', 'bro' ),
        	array('gecko', true, 'moz', 'bro' ),
        	array('netpositive', false, 'netp', 'bbro' ),
        	array('lynx', false, 'lynx', 'bbro' ),
        	array('elinks ', false, 'elinks', 'bbro' ),
        	array('elinks', false, 'elinks', 'bbro' ),
        	array('links2', false, 'links2', 'bbro' ),
        	array('links ', false, 'links', 'bbro' ),
        	array('links', false, 'links', 'bbro' ), 
        	array('w3m', false, 'w3m', 'bbro' ), 
        	array('webtv', false, 'webtv', 'bbro' ),
        	array('amaya', false, 'amaya', 'bbro' ),
        	array('dillo', false, 'dillo', 'bbro' ),
        	array('ibrowse', false, 'ibrowse', 'bbro' ),
        	array('icab', false, 'icab', 'bro' ), 
        	array('crazy browser', true, 'ie', 'bro' ), 
        	array('adsbot-google', false, 'google-ads', 'bot' ), 
        	array('answerbus', false, 'answerbus', 'bot' ), 
        	array('almaden', false, 'ibm', 'bot' ), 
        	array('ask jeeves', false, 'ask', 'bot' ), 
        	array('teoma', false, 'ask', 'bot' ), 
        	array('bpimagewalker', false, 'bp-imagewalker', 'bot' ), 
        	array('bhcbot', false, 'bhcbot', 'bot' ), 
        	array('boitho.com-dc', false, 'boitho', 'bot' ), 
        	array('comodospider', false, 'comodospider', 'bot' ),
        	array('domainsigmacrawler', false, 'domainsigmacrawler', 'bot' ),
        	array('dotbot', false, 'dotbot', 'bot' ), 
        	array('downnotifier', false, 'downnotifier', 'bot' ),
        	array('facebookexternalhit', false, 'facebook', 'bot' ),
        	array('facebot', false, 'facebook', 'bot' ),
        	array('fast-webcrawler', false, 'fast', 'bot' ),
        	array('fatbot', false, 'fatbot', 'bot' ),
        	array('gigabot', false, 'gigabot', 'bot' ),
        	array('googledocs', false, 'google-docs', 'bot' ),
        	array('gozaikbot', false, 'gozaikbot', 'bot' ),
        	array('headmasterseo', false, 'headmasterseo', 'bot' ),
        	array('hosttracker', false, 'hosttracker', 'bot' ),
        	array('hybridbot', false, 'hybridbot', 'bot' ),
        	array('ia_archiver', false, 'ia_archiver', 'bot' ), 
        	array('icc-crawler', false, 'icc-crawler', 'bot' ),
        	array('iltrovatore-setaccio', false, 'il-set', 'bot' ),
        	array('imagewalker', false, 'imagewalker', 'bot' ),
        	array('lexxebotr', false, 'lexxebotr', 'bot' ),
        	array('linkscanner', false, 'linkscanner', 'bot' ),
        	array('linkwalker', false, 'linkwalker', 'bot' ),
        	array('magpie-crawler', false, 'magpie-crawler', 'bot' ),
        	array('mediapartners-google', false, 'adsense', 'bot' ),
        	array('mj12bot', false, 'mj12bot', 'bot' ),
        	array('naverbot', false, 'naverbot', 'bot' ),
        	array('objectssearch', false, 'objectsearch', 'bot' ),
        	array('omgilibot', false, 'omgilibot', 'bot' ),
        	array('openbot', false, 'openbot', 'bot' ),
        	array('pinterest', false, 'pinterest', 'bot' ),
        	array('primalbot', false, 'primalbot', 'bot' ),
        	array('psbot', false, 'psbot', 'bot' ),
        	array('redback', false, 'redback', 'bot' ),
        	array('scooter', false, 'scooter', 'bot' ),
        	array('seoscanners', false, 'seoscanners', 'bot' ),
        	array('slackbot', false, 'slackbot', 'bot' ),
        	array('slack.com', false, 'slackbot', 'bot' ),
        	array('sogou', false, 'sogou', 'bot' ),
        	array('sosospider', false, 'sosospider', 'bot' ),
        	array('sohu-search', false, 'sohu', 'bot' ),
        	array('surdotlybot', false, 'surdotlybot', 'bot' ),
        	array('surveybot', false, 'surveybot', 'bot' ),
        	array('switescraper', false, 'switescraper', 'bot' ),
        	array('uxcrawlerbot', false, 'uxcrawlerbot', 'bot' ),
        	array('vbseo', false, 'vbseo', 'bot' ),
        	array('vegi bot', false, 'vegi-bot', 'bot' ),
        	array('xenu', false, 'xenu', 'bot' ),
        	array('yahoo link preview', false, 'yahoo-preview', 'bot' ),
        	array('yahoo-verticalcrawler', false, 'yahoo', 'bot' ),
        	array('yahoo! slurp', false, 'yahoo', 'bot' ),
        	array('slurp', false, 'inktomi', 'bot' ),
        	array('inktomi', false, 'inktomi', 'bot' ),
        	array('yahoo-mm', false, 'yahoomm', 'bot' ),
        	array('zyborg', false, 'looksmart', 'bot' ),
        	array('ahc', false, 'ahc', 'lib' ),
        	array('w3c_validator', false, 'w3c', 'lib' ),
        	array('wdg_validator', false, 'wdg', 'lib' ),
        	array('libwww-perl', false, 'libwww-perl', 'lib' ),
        	array('jakarta commons-httpclient', false, 'jakarta', 'lib' ),
        	array('java', false, 'java', 'lib' ),
        	array('okhttp', false, 'okhttp', 'lib' ),
        	array('python-urllib', false, 'python-urllib', 'lib' ),
        	array('ruby', false, 'ruby', 'lib' ),
        	array('winhttp', false, 'winhttp', 'lib' ),
        	array('curl', false, 'curl', 'dow' ),
        	array('favicon downloader', false, 'favicon-downloader', 'dow' ),
        	array('guzzle', false, 'guzzle', 'dow' ),
        	array('getright', false, 'getright', 'dow' ),
        	array('wget', false, 'wget', 'dow' ),
        	array('mozilla/4.', false, 'ns', 'bbro' ),
        	array('mozilla/3.', false, 'ns', 'bbro' ),
        	array('mozilla/2.', false, 'ns', 'bbro' )
	);

	// ----------------------------------------------------

	/**
	 * Function: __constructor
	 *
	**/
	public function __construct($_user_agent = "")
	{
		if(!empty($_user_agent))
		{
			$this->user_agent = $_user_agent;
			$this->parse($this->_user_agent);
		}
	}

	// -----------------------------------------------

	/**
	 * Function set_user_agent
	 *
	 * Set User Agent String to the class
	 *
	 * @access public
	 * @param  none
	 * @return String
	**/
	public function set_user_agent($_user_agent = '')
	{
		if(!empty($_user_agent))
		{
			$this->user_agent = $_user_agent;
		}
	}

	// -----------------------------------------------

	/**
	 * Function get_user_agent
	 *
	 * Recover the original string
	 *
	 * @access public
	 * @param  none
	 * @return String
	**/
	public function get_user_agent()
	{
		return $this->user_agent;
	}

	// -----------------------------------------------

	/**
	 * Function: parse
	 *
	 * parse the user agent string
	 *
	 * @access public
	 * @param  string
	 * @return none
	**/
	public function parse($_user_agent = '')
	{
		$user_agent = (empty($_user_agent)) ? $this->user_agent : $_user_agent;
		if(empty($user_agent))
		{
			return;
		}		

		$this->user_agent = $user_agent;
		$this->script_time();

		foreach($this->a_browser_types as $i => $browser_type)
		{
			// --------------------------------------------
			// Unpacks browser array, assigns to variables
			// need to not assign til found in string
			// --------------------------------------------
			list($browser, $dom_browser, $browser_working, $ua_type) = $browser_type;
			if(strstr($this->user_agent, $browser))
			{
				$this->b_safe_browser  = TRUE;
				$this->browser_name    = $browser;
				$this->browser_working = $browser_working;
				$this->ua_type         = $ua_type;

				switch ($this->browser_working) 
				{
					case 'ns':
						$this->b_safe_browser = FALSE;
						$this->browser_number = $this->get_item_version(
							$this->user_agent, 'mozilla');
						break;
					case 'blink':
						// -------------------------------
						// To get full number
						// -------------------------------
						if($this->browser_name == 'opr/')
						{
							$this->get_set_count('set', 0);
						}

						$this->browser_number = get_item_version(
							$this->user_agent, $this->browser_name);

						// -------------------------------
						// Assign rendering engine data
						// -------------------------------
						$this->layout_engine = 'blink';
						if(strstr($this->user_agent, 'blink'))
						{
							$this->layout_engine_nu_full = $this->get_item_version(
								$this->user_agent, 'blink');
						}
						else
						{
							$this->layout_engine_nu_full = $this->get_item_version(
								$this->user_agent, 'webkit');
						}
						$this->layout_engine_nu = get_item_math_number($this->browser_number);

						// ------------------------------------------------
						// This is to pull out specific webkit versions, 
						// safari, google-chrome, etc
						// ------------------------------------------------
						foreach($this->a_blink_types as $blink_type)
						{
							if(strstr($this->user_agent, $blink_type))
							{
								if($this->browser_name == 'opr/')
								{
									$this->get_set_count('set', 0);
								}

								$this->blink_type_number = $this->get_item_version(
									$this->user_agent, $blink_type);

								$this->browser_name = $blink_type;
								if($this->browser_name = 'opr/')
								{
									$this->get_set_count('set', 0);
								}

								$this->browser_number = $this->get_item_version(
									$this->user_agent, $this->browser_name);
							}
						}

						if($this->browser_name == 'opr/')
						{
							$this->browser_name = 'opera';
						}
						break;
					case 'dillo':
						$this->browser_number = $this->get_item_version(
							$this->user_agent, $this->browser_name);

						// -------------------------------------------
						// Assign rendering engine data
						// -------------------------------------------
						$this->layout_engine    	 = 'dillo';
						$this->layout_engine_nu 	 = $this->get_item_math_number(
							$this->browser_number);
						$this->layout_engine_nu_full = $this->browser_number;
						break;
					case 'edge':
						$this->browser_number = $this->get_item_version(
							$this->user_agent, $this->browser_name);

						// --------------------------------------------
						// Assign rendering engine data
						// --------------------------------------------
						$this->layout_engine 		 = 'edgehtml';
						$this->layout_engine_nu 	 = get_item_math_number(
							$this->browser_number);
						$this->layout_engine_nu_full = $this->browser_number;
						break;
					case 'khtml':
						// ---------------------------------------------
						// Note that this is the KHTML version number
						// ---------------------------------------------
						$this->browser_number 		 = $this->get_item_version(
							$this->user_agent, $this->browser_name);

						// ----------------------------------------------
						// Assign rendering engine data
						// ----------------------------------------------
						$this->layout_engine 		 = 'khtml';
						$this->layout_engine_nu 	 = $this->get_item_math_number(
							$this->browser_number);
						$this->layout_engine_nu_full = $this->browser_number;

						// -----------------------------------------------
						// This is to pull out specific khtml versions,
						// konqueror
						// -----------------------------------------------
						foreach($this->a_khtml_types as $khtml_type)
						{
							if(strstr($this->user_agent, $khtml_type))
							{
								$this->khtml_type_number = $this->get_item_version(
									$this->user_agent, $khtml_type);
								$this->browser_name   = $khtml_type;
								$this->browser_number = $this->get_item_version(
									$this->user_agent, $this->browser_name);
							}
						}
						break;
					case 'moz':
						// ----------------------------------------------
						// This will return alpha and beta versions,
						// numbers, if present
						// ---------------------------------------------
						$this->get_set_count('set', 0);
						$this->moz_rv_full = $this->get_item_version(
							$this->user_agent, 'rv:');

						// -----------------------------------------------
						// This slices them back off for math comparisons
						// -----------------------------------------------
						$this->moz_rv = floatval($this->moz_rv_full);

						foreach($this->a_gecko_types as $gecko_type)
						{
							if(strstr($this->user_agent, $gecko_type))
							{
								$this->moz_type = $gecko_type;
								$this->moz_type_number = $this->get_item_version(
									$this->user_agent, $this->moz_type);
							}
						}

						// -----------------------------------------------
						// This is necesary to proctect against false id'ed
						// moz'es and new moz'es. this correct for galeon
						// -----------------------------------------------
						if(!$moz_rv)
						{
							// ---------------------------------------------
							// You can use this if you are running php >= 4.2
							// ---------------------------------------------
							$this->moz_rv = floatval($this->moz_type_number);
							$this->moz_rv_full = $this->moz_type_number;
						}

						// -------------------------------------------------
						// this corrects the version name in case it went
						// to the default 'rv' for the test
						// -------------------------------------------------
						if($this->moz_rv)
						{
							$this->moz_type = 'mozilla';
						}

                        			// -------------------------------------------------
                        			// the moz version will be taken from the rv number, 
                        			// see notes above for rv problems
                        			// -------------------------------------------------
                        			$this->browser_number = $this->moz_rv;

                        			// -------------------------------------------------
                        			// gets the actual release date, necessary if you 
                        			// need to do functionality tests
                        			// --------------------------------------------------
                        			$this->get_set_count('set', 0);
                        			$this->moz_release_date = $this->get_item_version( 
                        				$this->browser_user_agent, 'gecko/' );

                        			// --------------------------------------------------
                        			// assign rendering engine data
                        			// --------------------------------------------------
                        			$this->layout_engine = 'gecko';
                        			$this->layout_engine_nu = $this->moz_rv;
                        			$this->layout_engine_nu_full = $this->moz_rv_full;

                        			// ---------------------------------------------------
                        			// Test for mozilla 0.9.x / netscape 6.x test your 
                        			// javascript/CSS to see if it works in these mozilla 
                        			// releases, if it does, just default it to: 
                        			// $b_safe_browser = true;
                        			// ---------------------------------------------------
                        			if(($this->moz_release_date < 20020400) 
                        			|| ($this->moz_rv < 1))
                        			{
                        				$this->b_safe_browser = FALSE;
                        			}
                        			break;
                    			case 'ie':
                    				$this->b_gecko_ua = FALSE;
                        			// ---------------------------------------------------
                        			// note we're adding in the trident/ search to return 
                        			// only first instance in case of msie 8, and we're 
                        			// triggering the  break last condition in the test, 
                        			// as well as the test for a second search string, trident/
                        			// Sample: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; 
						// Trident/6.0)
                        			//
                        			// Handle the new msie 11 ua syntax (search for rv:), sample:
                        			// Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko
                        			// so assign msie value back here
                        			// ----------------------------------------------------
                        			if(strstr($this->user_agent, 'rv:' ))
                        			{
                        				$this->browser_name = 'msie';
                        				$this->b_gecko_ua = TRUE;
                            				$this->get_set_count( 'set', 0 );

                            				$this->$browser_number = $this->get_item_version( 
                            					$this->user_agent, 'rv:', '', '' );
                        			}
                        			else 
                        			{
                        				$this->browser_number = $this->get_item_version(
                        					$this->user_agent, $this->browser_name, true, 'trident/');
                        			}

                        			$this->get_set_count( 'set', 0 );
			
                        			$this->layout_engine_nu_full = $this->get_item_version(
                        				$this->user_agent, 'trident/', '', '' );

                        			// ----------------------------------------------------
                        			// construct the proper real number. For example, 
                        			// trident 4 is msie 8
                        			// ----------------------------------------------------
                        			if($this->layout_engine_nu_full) 
                        			{
                        				$this->layout_engine_nu = $this->get_item_math_number( 
                        					$this->layout_engine_nu_full);

                        				$this->layout_engine = 'trident';

                        				// ------------------------------------------------
                            				// in compat mode, browser shows as msie 7, for now, 
                            				// check in future msie versions. Note that this 
                            				// isn't used in new gecko type ua, so no compat 
                            				// mode switch
                            				// -------------------------------------------------
                            				if(strstr($this->browser_number, '7.' ) 
                            				&& !$this->b_gecko_ua) 
                            				{
                            					$this->true_ie_number = $this->get_item_math_number(
                            						$this->browser_number) + (intval($this->layout_engine_nu)-3);
                            				}
                            				else
                            				{
                            					$this->true_ie_number = $this->browser_number;
                            				}

                            				// -------------------------------------------------
                            				// this is to pull out specific trident versions, 
                            				// ucbrowser, etc...
                            				// -------------------------------------------------
                            				foreach($this->a_trident_types as $trident_type)
                            				{
                            					if(strstr($this->user_agent, $trident_type))
                            					{
                            						$this->trident_type = $trident_type;
									$this->trident_type_number = $this->get_item_version($this->user_agent, $trident_type);
								}
							}

							// --------------------------------------------------
							// note the string msie does not appear in gecko 
							// type msie useragents
							// ---------------------------------------------------
							if(!$this->trident_type && $this->b_gecko_ua)
							{
								$this->trident_type = 'msie';
								$this->trident_type_number = $this->browser_number;
                            				}
                        			}

						// -----------------------------------------------------------
                                                // note: trident is engine from ie 4 onwards, but only shows 
						// after ie 8 but msie 7 is trident 3.1, and no trident numbers 
						// are known for earlier
                                                // ------------------------------------------------------------
                                                else
						if(intval($browser_number) <= 7 && intval($browser_number) >= 4 )
						{
                                                        $this->layout_engine = 'trident';
                                                        if(intval($this->browser_number) == 7)
							{
                                                                $this->layout_engine_nu_full = '3.1';
                                                                $this->layout_engine_nu = '3.1';
                                                        }
                                                }
						// -----------------------------------------------------------
                                                // the 9 series is finally standards compatible, html 5 etc, 
						// so worth a new id 
						// -----------------------------------------------------------
                                                if($this->browser_number >= 9 )
						{
                                                        $this->ie_version = 'ie9x';
                                                }
						// -----------------------------------------------------------
                                                // 7/8 were not yet quite to standards levels but getting there
						// -----------------------------------------------------------
                                                else
						if($this->browser_number >= 7 )
						{
                                                        $this->ie_version = 'ie7x';
                                                }
						// ------------------------------------------------------------
                                                // then test for IE 5x mac, that's the most problematic IE out 
						// there 
						// ------------------------------------------------------------
                                                else
						if(strstr($this->user_agent, 'mac'))
						{
                                                        $this->ie_version = 'ieMac';
                                                }
						// ------------------------------------------------------------
                                                // ie 5/6 are both very weak in standards compliance
						// ------------------------------------------------------------
                                                else
						if($this->browser_number >= 5 )
						{
                                                        $this->ie_version = 'ie5x';
                                                }
                                                else
						if(($this->browser_number > 3) 
						&& ($this->browser_number < 5))
						{
                                                        $this->b_dom_browser = false;
                                                        $this->ie_version    = 'ie4';
							// -------------------------------------------------------
                                                        // this depends on what you're using the script for, make 
							// sure this fits your needs
							// -------------------------------------------------------
                                                        $this->b_safe_browser = true;
                                                }
                                                else 
						{
                                                        $this->ie_version  = 'old';
                                                        $this->b_dom_browser = false;
                                                        $this->b_safe_browser = false;
                                                }
                                                break;
                    			case 'op':
						if($this->browser_name == 'opr/')
						{
							$this->browser_name = 'opr';
						}

						$this->browser_number = $this->get_item_version(
							$this->user_agent, $this->browser_name);

						// --------------------------------------------------------------
						// opera is leaving version al 9.80 (or xx) for 10.x - see this
						// explanation
						// http://dev.opera.com/articles/view/opera-ua-string-changes
						// --------------------------------------------------------------
						if(strstr($this->browser_number, '9.0') 
						&& strstr($this->user_agent, 'version/'))
						{
							$this->get_set_count('set', 0);
							$this->browser_number = $this->get_item_version(
								$this->user_agent, 'version/');
						}

						$this->get_set_count('set', 0);
						$this->layout_engine_nu_full = $this->get_item_version(
							$this->user_agent, 'presto/');

						if($this->layout_engine_nu_full)
						{
							$this->layout_engine = 'presto';
							$this->layout_engine_nu = $this->get_item_math_number(
								$this->layout_engine_nu_full);
						}

						if(!$this->layout_engine_nu_full 
						&& $this->browser_name == 'opr') 
						{
							if(strstr($this->browser_user_agent, 'blink'))
							{
								$this->layout_engine_nu_full = $this->get_item_version(
									$this->browser_user_agent, 'blink' );
                                                        }
                                                        else 
							{
                                                                $this->layout_engine_nu_full = $this->get_item_version(
									$this->browser_user_agent, 'webkit' );
                                                        }

                                                        $this->layout_engine_nu = $this->get_item_math_number($this->layout_engine_nu_full );

							// ---------------------------------------------------
                                                        // assign rendering engine data
							// ---------------------------------------------------
                                                        $this->layout_engine = 'blink';
                                                        $this->browser_name = 'opera';
                                                }
						// -------------------------------------------------
                                                // opera 4 wasn't very useable. 
						// -------------------------------------------------
                                                if($this->browser_number < 5)
						{
                                                        $this->b_safe_browser = false;
                                                }

                                                break;
					// ----------------------------------------------------
					// NOTE: webit return always the webkit version number,
					//       not the specific user agent version, ie, 
					//       webkit 583, not chrome 0.3
					// ----------------------------------------------------
					case 'webkit':
						// -------------------------------------------------
						// NOTE that this is the Webkit version number
						// -------------------------------------------------
						$this->browser_number = $this->get_item_version($this->user_agent, $this->browser_name);

						// -------------------------------------------------
						// Assign rendering engine data
						// -------------------------------------------------
						$this->layout_engine = 'webkit';
						$this->layout_engine_nu = $this->get_item_math_number($this->browser_number);
						$this->layout_engine_nu_full = $this->browser_number;

						// ------------------------------------------------
						// this is to pull out specific webkit versions, 
						// safari, google-chrome etc...
						// ------------------------------------------------
						foreach($this->a_webkit_types as $webkit_type)
						{
							if(strstr($this->user_agent, $webkit_type))
							{
								if($webkit == 'omniweb')
								{
									$this->get_set_count('set', 2);
								}

								$this->webkit_type_number = $this->get_item_version(
									$this->user_agent, $webkit_type);

								// ---------------------------------
								// epiphany hack
								// ---------------------------------
								if($webkit == 'gtklauncher')
								{
									$this->browser_name = 'epiphany';
								}
								else
								{
									$this->browser_name = $webkit;
								}

								if(($webkit == 'chrome' || $webkit == 'chromium') 
								&& $this->get_item_math_number($this->webkit_type_number) >= 28)
								{
									if(strstr($this->user_agent, 'blink'))
									{
										$this->layout_engine_nu_full = 
											$this->get_item_number($this->user_agent, 'blink');
										$this->layout_engine_nu = 
											$this->get_item_math_number($this->layout_engine_nu_full);
									}

									// ---------------------------------
									// Assign rendering engine data
									// ---------------------------------
									$this->layout_engine = 'blink';
								}

								$this->browser_number = $this->get_item_version(
									$this->user_agent, $this->browser_name);
							}
						}
					
						break;
					default:
						$this->browser_number = $this->get_item_version(
							$this->user_agent, $browser_name);
						break;
				}

				$this->b_success = true;
				break;
			}
		}

		// ---------------------------------------------------
		//  Assign defaults if the browser was not found
		// in the loop test
		// ---------------------------------------------------
		if(!$this->b_success)
		{
			// ----------------------------------------------
			// Delete this part if you want an unknown 
			// browser returned
			// ----------------------------------------------
			$this->browser_name = substr($this->user_agent, 0, strcspn($this->user_agent, '();'));

			// ----------------------------------------------
			// This extracts just the browser name from the 
			// string, if something usable was found
			// ----------------------------------------------
			if($this->browser_name 
			&& preg_match('/[^0-9][a-z]*-*\ *[a-z]*\ *[a-z]*/', $this->browser_name, $a_unhandle_browser))
			{
				$this->browser_name = $a_unhandle_browser[0];
				
				if($this->browser_name == 'blackberry')
				{
					$this->get_set_count('set', 0);
				}

				$this->browser_number = $this->get_item_version($this->user_agent, $this->browser_name);
			}
			else
			{
				$this->browser_name   = 'NA';
				$this->browser_number = 'NA';
			}

			// ------------------------------------------------
			// Then uncomment this part
			// $this->browser_name = '';
		}

		// ----------------------------------------------------------------
		// get os data, mac os x test requires browser/version information
		// this is a change from older scripts
		// ----------------------------------------------------------------
		if($this->b_os_test)
		{
			$this->a_os_data = $this->get_os_data($this->user_agent, 
				$this->browser_working, $this->browser_number);
			$this->os_type   = $this->a_os_data[0];
			$this->os_number = $this->a_os_data[1];
		}

		// ----------------------------------------------------------------
		// This ends the run through on if clause, set the boolean to 
		// true so the function won't retest everything
		// ----------------------------------------------------------------
		$this->b_repeat = true;
	
		// ----------------------------------------------------------------
		// pulls out primary version number from more complex string, like
		// 7.5a use this for numeric version comparison
		// ----------------------------------------------------------------
		$this->browser_math_number = $this->get_item_math_number(
			$this->browser_number);

		if($this->b_mobile_test)
		{
			$this->mobile_test = $this->check_is_mobile($this->user_agent);
			if($this->mobile_test)
			{
				$this->a_mobile_data = $this->get_mobile_data($this->user_agent);
				$this->au_type = 'mobile';
			}
		}

		/*
		switch ( $which_test ) 
		{
                	case 'math_number':
                        	$which_test = 'browser_math_number';
                        	break;
                	case 'number':
                        	$which_test = 'browser_number';
                        	break;
                	case 'browser':
                        	$which_test = 'browser_working';
                        	break;
                	case 'moz_version':
                        	$which_test = 'moz_data';
                        	break;
                	case 'true_msie_version':
                        	$which_test = 'true_ie_number';
                        	break;
                	case 'type':
                        	$which_test = 'ua_type';
                        	break;
                	case 'webkit_version':
                        	$which_test = 'webkit_data';
                        	break;
        	}
		*/

		// ----------------------------------------------------
		// Assemble these first so they can be included in 
		// full return data, using static variables. NOTE that
		// there's no need to keep repacking these every time
		// the script is called
		// ----------------------------------------------------
		if ( !$this->a_engine_data ) 
		{
                	$this->a_engine_data = array( $this->layout_engine, 
				$this->layout_engine_nu_full, $this->layout_engine_nu );
        	}

		if ( !$this->a_blink_data ) 
		{
                	$this->a_blink_data = array( $this->blink_type, 
				$this->blink_type_number, $this->browser_number );
        	}
        
		if ( !$this->a_khtml_data ) 
		{
                	$this->a_khtml_data = array( $this->khtml_type, 
				$this->khtml_type_number, $this->browser_number );
        	}

	        if ( !$this->a_moz_data ) 
		{
                	$this->a_moz_data = array( $this->moz_type, 
				$this->moz_type_number, $this->moz_rv, 
				$this->moz_rv_full, $this->moz_release_date );
        	}

	        if ( !$this->a_trident_data ) 
		{
                	$this->a_trident_data = array( $this->trident_type, 
				$this->trident_type_number, $this->layout_engine_nu, 
				$this->browser_number );
        	}

	        if ( !$this->a_webkit_data ) 
		{
                	$this->a_webkit_data = array( $this->webkit_type, 
				$this->webkit_type_number, $this->browser_number );
        	}

		$this->run_time = $this->script_time();

		// -----------------------------------------------------------------------
		// Now send the actual engine number to the html type function
		// -----------------------------------------------------------------------
		if($this->layout_engine_nu)
		{
			$this->html_type = $this->get_html_level($this->layout_engine, $this->layout_engine_nu);
		}

		// ------------------------------------------------------------------------
		// Then pack the primary data array
		// ------------------------------------------------------------------------
		if(!$this->a_full_assoc_data)
		{
			$this->a_full_assoc_data = array(
                        	'browser_working' 	=> $this->browser_working,
                        	'browser_number' 	=> $this->browser_number,
                        	'ie_version' 		=> $this->ie_version,
                        	'dom' 			=> $this->b_dom_browser,
                        	'safe' 			=> $this->b_safe_browser,
                        	'os' 			=> $this->os_type,
                        	'os_number' 		=> $this->os_number,
                        	'browser_name' 		=> $this->browser_name,
                        	'ua_type' 		=> $this->ua_type,
                        	'browser_math_number' 	=> $this->browser_math_number,
                        	'moz_data' 		=> $this->a_moz_data,
                        	'webkit_data' 		=> $this->a_webkit_data,
                        	'mobile_test' 		=> $this->mobile_test,
                        	'mobile_data' 		=> $this->a_mobile_data,
                        	'true_ie_number' 	=> $this->true_ie_number,
                        	'run_time' 		=> $this->run_time,
                        	'html_type' 		=> $this->html_type,
                        	'engine_data' 		=> $this->a_engine_data,
                        	'trident_data' 		=> $this->a_trident_data,
                        	'blink_data' 		=> $this->a_blink_data
			);
		}

		return $this->a_full_assoc_data;
	}

	// --------------------------------------------------------------------

	/**
	 * Function: get_item_math_number
	 *
	 * Recoger the number of the item
	 *
	 * @access protected
	 * @param  string
	 * @return string
	 ****/
	protected function get_item_math_number($pv_browser_number)
	{
		$browser_math_number = '';
		if($pv_browser_number && preg_match('/^[0-9]*\.*[0-9]*/', $pv_browser_number, $a_browser_math_number))
		{
			$browser_math_number = $a_browser_math_number[0];
			// print_r($a_browser_math_number);
		}

		return $browser_math_number;
	}

	// --------------------------------------------------------------------

	protected function get_os_data($pv_browser_string, $pv_browser_name, $pv_version_number)
	{
		// -----------------------------------
		// Initialize variables
		// -----------------------------------
		$os_working_type 	= '';
		$os_working_number 	= '';
		
		// ------------------------------------------------------
		// pack the os array. use this order since son navigator
		// user agents will put 'macintosh' in the navegator 
		// user agent stirng which would make the nt test register 
		// true
		// -----------------------------------------------------
		$a_mac = array('intel mac', 'OS X', 'ppc mac', 'mac68k');

		// -------------------------------------------------------
		// same logic, check in order to catch the os's in order, 
		// last is always default item 
		// -------------------------------------------------------
		$a_unix_types = array( 
			'dragonfly', 	'freebsd', 	'openbsd', 	'netbsd', 	'bsd', 	'unixware',
			'solaris', 	'sunos', 	'sun4', 	'sun5', 	'suni86', 
			'sun', 		'irix5', 	'irix6', 	'irix', 	'hpux9', 
			'hpux10', 	'hpux11', 	'hpux', 	'hp-ux', 	'aix1', 
			'aix2', 	'aix3', 	'aix4', 	'aix5', 	'aix', 
			'sco', 		'unixware', 	'mpras', 	'reliant', 	'dec', 
			'sinix', 	'unix' 
		);

		// -------------------------------------------------------
		// only sometimes will you get a linux distro to 
		// id itself... 
		// -------------------------------------------------------
		$a_linux_distros = array( 
			' cros ', 	'ubuntu', 	'kubuntu', 	'xubuntu', 	'mepis', 
			'xandros',	'linspire', 	'winspire', 	'jolicloud', 	'sidux', 
			'kanotix', 	'debian', 	'opensuse', 	'suse',		'fedora', 
			'redhat', 	'slackware', 	'slax', 	'mandrake', 	'mandriva', 
			'gentoo', 	'sabayon',	'linux' 
		);

		$a_linux_process = array ( 'i386', 'i586', 'i686', 'x86_64' );	/** not use currently **/
        
		// -------------------------------------------------------
		// note, order of os very important in os array, you will 
		// get failed ids if changed windows 10 shows android 
		// in ua string
		// -------------------------------------------------------
		$a_os_types = array( 
			'blackberry', 	'iphone', 	'palmos', 	'palmsource', 	'symbian', 
			'beos', 	'os2',		'amiga', 	'webtv', 	'macintosh', 
			'mac_', 	'mac ', 	'nt', 		'win',		'android',
			$a_unix_types, 	$a_linux_distros 
		);

		foreach($a_os_types as $pos => $os_type)
		{
			// ---------------------------------------------------
			// Unpacks os array, assigns to variables a_os_working
			// ---------------------------------------------------
			if(!is_array($os_type) 
			&& strstr($pv_browser_string, $os_type)
			&& !strstr($pv_browser_string, "linux"))
			{
				$os_working_type = $os_type;
				switch($os_working_type)
				{
					// ------------------------------------
					// Most windows now uses: NT X.Y syntax
					// ------------------------------------
					case 'nt':
						// --------------------------------------------------------
						// This return either a number, like 3, or 5.1. It does
						// not return any alpha/beta type data for the os version
						// --------------------------------------------------------
						preg_match ( '/nt ([0-9]+[\.]?[0-9]?)/', $pv_browser_string, $a_nt_matches );
						if(isset($a_nt_matches[1]))
						{
							$os_working_number = $a_nt_matches[1];
						}
						break;
					case 'win':
                                        	// ---------------------------------------------------------
						// windows vista, for opera ID
						// ---------------------------------------------------------
						if(strstr($pv_browser_string, 'vista'))
						{
							$os_working_number = 6.0;
							$os_working_type = 'nt';
						}
						// ----------------------------------------------------------
						// windows xp, for opera ID 
						// ----------------------------------------------------------
						elseif(strstr($pv_browser_string, 'xp'))
						{
							$os_working_number = 5.1;
							$os_working_type = 'nt';
						}
						// ---------------------------------------------------------
						// windows server 2003, for opera ID 
						// ---------------------------------------------------------
						elseif(strstr($pv_browser_string, '2003')) 
						{
							$os_working_number = 5.2;
							$os_working_type = 'nt';
						}
						// ----------------------------------------------------------
						// windows CE 
						// ----------------------------------------------------------
						elseif(strstr($pv_browser_string, 'windows ce'))
						{
							$os_working_number = 'ce';
							$os_working_type = 'nt';
						}
						elseif(strstr($pv_browser_string, '95')) 
						{
							$os_working_number = '95';
						}
						elseif((strstr( $pv_browser_string, '9x 4.9'))
						|| (strstr( $pv_browser_string, ' me' ))) 
						{
							$os_working_number = 'me';
						}
						elseif(strstr($pv_browser_string, '98'))
						{
							$os_working_number = '98';
						}
						// -------------------------------------------------
						// windows 2000, for opera ID
						// -------------------------------------------------
						elseif(strstr($pv_browser_string, '2000'))
						{
							$os_working_number = 5.0;
							$os_working_type = 'nt';
						}
						break;
					case 'mac ':
					case 'mac_':
					case 'macintosh':
						$os_working_type = 'mac';
						if(strstr($pv_browser_string, 'os x'))
						{
							// ------------------------------------------------------
							// if it doesn't have a version number, it is os x; 
							// ------------------------------------------------------
							if(strstr($pv_browser_string, 'os x '))
							{
								// ------------------------------------------------
								// numbers are like: 10_2.4, others 10.2.4 
								// ------------------------------------------------
								$os_working_number = str_replace( '_', '.', $this->get_item_version($pv_browser_string, 'os x'));
							}
							else 
							{
								$os_working_number = 10;
							}
						}
						// -------------------------------------------------------------
						// this is a crude test for os x, since safari, camino, ie 5.2, 
						// & moz >= rv 1.3 are only made for os x
						// -------------------------------------------------------------
                                        	elseif($pv_browser_name == 'saf'
                                                 || $pv_browser_name == 'cam'
                                                 || (($pv_browser_name == 'moz') && ($pv_version_number >= 1.3))
                                                 || (($pv_browser_name == 'ie') && ($pv_version_number >= 5.2)))
						{
							$os_working_number = 10;
						}
						break;
					case 'iphone':
						$os_working_number = 10;
						break;
					default:
						break;
				}
			}

			// -----------------------------------------------------------
			// check taht it's an array, check it's the second to last
			// item in the main os array, the unix on that is
			// ------------------------------------------------------------
			elseif(is_array($os_type) && ($pos == count($a_os_types) - 2))
			{
				$j_count = count($os_type);
				for ($j = 0; $j < $j_count; $j++) 
				{
					if(strstr($pv_browser_string, $os_type[$j]))
					{
						// ---------------------------------------------------------
						// if the os is in the unix array, it's unix, obviously...
						// ---------------------------------------------------------
						$os_working_type = 'unix';

						// ---------------------------------------------------------
						// assign sub unix version from the unix array
						// ---------------------------------------------------------
						$os_working_number = ( $os_type[$j] != 'unix' ) 
								? $os_type[$j] 
								: '';
                                        	break;
                                	}
                        	}
			}
			elseif(is_array($os_type) && ($pos == count($a_os_types) - 1))
			{
				$j_count = count($os_type);
				for ($j = 0; $j < $j_count; $j++)
				{
					if(strstr( $pv_browser_string, $os_type[$j]))
					{
						$os_working_type = 'lin';

						// ---------------------------------------------
						// assign linux distro from the linux array, 
						// there's a default search for 'lin', if it's 
						// that, set version to ''
                                        	// ---------------------------------------------
						$os_working_number = ( $os_type[$j] != 'linux' ) 
							? $os_working_data[$j] 
							: '';
                                        	break;
					}
				}
			}
		}

		// -------------------------------------------
		// pack the os data array for return to main
		// function
		// -------------------------------------------
		$a_os_data = array($os_working_type, $os_working_number);

		return $a_os_data;
	}

	// --------------------------------------------------------------------

	/**
	 * Function: get_item_version
	 *
	 * recover item version
	 *
	 * @access protected
	 * @param  string user_agent
	 * @param  string search 
	 * @param  string break_last
	 * @param  string extra search
	*********/
	protected function get_item_version( $pv_browser_user_agent, $pv_search_string, $pv_b_break_last='', $pv_extra_search='' )
	{
		// ----------------------------------------------------
		// 12 is the longest that will be required, handles 
		// release dates: 20020323; 0.8.0+ 
		// ----------------------------------------------------
		$substring_length = 15;
		$start_pos = 0; 		// set $start_pos to 0 for first iteration

		// --------------------------------------------------------
		// initialize browser number, will return '' if not found
		// --------------------------------------------------------
		$string_working_number = '';

		// ---------------------------------------------------------
		// use the passed parameter for $pv_search_string
		// start the substring slice right after these moz search 
		// strings there are some cases of double msie id's, first 
		// in string and then with then number
		// $start_pos = 0;
		// this test covers you for multiple occurrences of string, 
		// only with ie though with for example google bot you want 
		// the first occurance returned, since that's where the
		// numbering happens
		// ---------------------------------------------------------
		for ( $i = 0; $i < 4; $i++ ) 
		{
			// ----------------------------------------------------
			// start the search after the first string occurrence
			// ----------------------------------------------------
			if ( strpos( $pv_browser_user_agent, $pv_search_string, $start_pos ) !== false ) 
			{
				// ----------------------------------------------------
				// update start position if position found 
				// ----------------------------------------------------
				$start_pos = strpos( $pv_browser_user_agent, $pv_search_string, $start_pos ) + 
					strlen( $pv_search_string );
                        
				// -----------------------------------------------------
				// msie (and maybe other userAgents requires special 
				// handling because some apps inject a second msie, 
				// usually at the beginning, custom modes allow breaking 
				// at first instance if $pv_b_break_last $pv_extra_search 
				// conditions exist. Since we only want this test to run 
				// if and only if we need it, it's triggered by caller 
				// passing these values.
				// ------------------------------------------------------
				if(!$pv_b_break_last
				|| ($pv_extra_search && strstr($pv_browser_user_agent, $pv_extra_search)))
				{
					break;
				}
			}
			else
			{
				break;
			}
		}

		// ------------------------------------------------
		// Handles things like extra omniweb/v456, gecko/, 
		// blackberry9700 also corrects for the omniweb 'v'
		// ------------------------------------------------
		$start_pos += $this->get_set_count('get');

		// ------------------------------------------------
		// Find the space, or parentheses that ends the
		// number
		// -------------------------------------------------
		$string_working_number = substr($string_working_number, 0, strcspn($string_working_number, ' );/'));

		// -------------------------------------------------
		// make sure the returned value is actually the id 
		// number and not a string otherwise return ''
		// -------------------------------------------------
        	// strcspn( $string_working_number, '0123456789.') == strlen( $string_working_number)
        	// if ( preg_match("/\\d/", $string_working_number) == 0 )
		if(!is_numeric(substr($string_working_number, 0, 1)))
		{
			$string_working_number = '';
		}
		
		// $string_working_number = strrpos( $pv_browser_user_agent, $pv_search_string );
		return $string_working_number;
	}

	// --------------------------------------------------------------------

	protected function get_set_count($pv_type, $pv_value = '')
	{
		$this->slice_increment;
		$return_value = '';
		
		switch($pv_type)
		{
			case 'get':
				// ----------------------------------------
				// set if unset, ie, first use. note that
				// empty and isset are not good tests here
				// -----------------------------------------
				if(is_null($this->slice_increment))
				{
					$this->slice_increment = 1;
				}

				$return_value = $this->slice_increment;
				$this->slice_increment = 1;
				
				return $return_value;
				break;
			case 'set':
				$this->slice_increment = $pv_value;
				break;
		}
	}

	// --------------------------------------------------------------------

	protected function check_is_mobile($pv_browser_user_agent)
	{
		$mobile_working_test = '';

		// -------------------------------------------------------------
		// these will search for basic mobile hints, this should catch 
		// most of them, first check known hand held device os, then 
		// check device names, then mobile browser names This list is 
		// almost the same but not exactly as the 4 arrays in function below
		// -------------------------------------------------------------
		$a_mobile_search = array(
			// ------------------------------------------------------
			// Make sure to use only data here that always will be a 
			// mobile, so this list is not identical to the list of 
			// get_mobile_data armv may be a laptop like cros so 
			// don't use 'armv' or 'linux armv'
			// ------------------------------------------------------
			// OS
			// -------------------------------------------------------
			'android', 		'blackberry', 		'epoc', 
			'palmos', 		'palmsource', 		'windows ce',
			'windows phone os', 	'windows phone', 	'symbianos', 
			'symbian os', 		'symbian', 		'webos',
			// -------------------------------------------------------
			//  devices - ipod before iphone or fails
			// -------------------------------------------------------
			'benq', 		'blackberry', 		'danger hiptop', 
			'ddipocket', 		' droid', 		'ipad', 
			'ipod', 		'iphone', 		'kindle', 
			'kobo', 		'lge-cx', 		'lge-lx', 
			'lge-mx', 		'lge vx', 		'lge ', 
			'lge-', 		'lg;lx',		'nexus', 
			'nintendo wii', 	'nokia', 		'nook', 
			'palm', 		'pdxgw', 		'playstation', 
			'rim',			'sagem', 		'samsung', 
			'sec-sgh', 		'sharp', 		'sonyericsson', 
			'sprint', 		'zune', 		'j-phone', 
			'n410', 		'mot 24', 		'mot-', 
			'htc-', 		'htc_', 		'htc ', 
			'playbook', 		'sec-', 		'sie-m', 
			'sie-s', 		'spv ', 		'touchpad', 
			'vodaphone', 		'smartphone', 		'midp', 
			'mobilephone',
			// -------------------------------------------------------
			// browsers
			// --------------------------------------------------------
			'avantgo', 		'blazer', 		'elaine', 
			'eudoraweb', 		'fennec', 		'iemobile',  
			'minimo', 		'mobile safari', 	'mobileexplorer', 
			'opera mobi', 		'opera mini', 		'netfront', 
			'opwv', 		'polaris', 		'puffin', 
			'samsungbrowser', 	'semc-browser', 	'skyfire', 
			'up.browser',		'ucweb', 		'ucbrowser', 
			'webpro/', 		'wms pie', 		'xiino',
			// --------------------------------------------------------
			// Services - astel out of business
			// ---------------------------------------------------------
			'astel', 		'docomo', 		'novarra-vision', 
			'portalmmm', 		'reqwirelessweb', 	'vodafone'
		);

		// -----------------------------------------------------------
		// then do basic mobile type search, this uses data from: 
		// get_mobile_data()
		// ------------------------------------------------------------
		$j_count = count($a_mobile_search);
		for ($j = 0; $j < $j_count; $j++) 
		{
			if(strstr($pv_browser_user_agent, $a_mobile_search[$j]))
			{
				// ---------------------------------------------------
				// this handles compat/pre msie 9 mode zune embedded 
				// in ua via registry 
				// ---------------------------------------------------
				if($a_mobile_search[$j] != 'zune' || strstr($pv_browser_user_agent, 'iemobile'))
				{
					$mobile_working_test = $a_mobile_search[$j];
					break;
				}
			}
		}

		return $mobile_working_test;
	}

	// --------------------------------------------------------------------

	/**
	 * Function: get_mobile_data
	 *
	 * Return the mobile informaition
	 *
	 * @access protected
 	 * @param  string
	 * @return array
	 **********/
	protected function get_mobile_data($pv_browser_user_agent)
	{
	        $mobile_browser = '';
	        $mobile_browser_number = '';
	        $mobile_device = '';
	        $mobile_device_number = '';
	        $mobile_os = ''; /** will usually be null, sorry **/
	        $mobile_os_number = '';
	        $mobile_server = '';
	        $mobile_server_number = '';
	        $mobile_tablet = '';

		// ---------------------------------------------------
		// browsers, show it as a handheld, but is not the os
		// note: crios is actuall chrome on ios, uc need to be 
		// before safari Mozilla/5.0 (Windows Phone 10.0; 
		// Android 4.2.1; DEVICE INFO) AppleWebKit/537.36 
		// (KHTML, like Gecko) Chrome/42.0.2311.135 Mobile 
		// Safari/537.36 Edge/12.<OS build number>
		// ---------------------------------------------------
		$a_mobile_browser = array( 
			'avantgo', 		'blazer', 		'crios', 
			'elaine', 		'eudoraweb',		'fennec', 
			'iemobile',  		'minimo', 		'ucweb', 
			'ucbrowser', 		'mobile safari', 	'mobileexplorer',
			'opera mobi', 		'opera mini', 		'netfront', 
			'opwv', 		'polaris', 		'puffin', 
			'samsungbrowser',	'semc-browser', 	'silk', 
			'steel', 		'ultralight', 		'up.browser', 
			'webos', 		'webpro/', 		'wms pie', 
			'xiino' 
		);

		// -------------------------------------------------------
		// This goes from easiest to detect to hardest, so don't 
		// use this for output unless you clean it up more is my 
		// advice. Special Notes: do not include milestone in 
		// general mobile type test above, it's too generic
		//
		// Note: we can safely now test for zune because the 
		// initial test shows zune with iemobile in ua
		// -------------------------------------------------------
		$a_mobile_device = array( 
			'benq', 		'blackberry', 		'danger hiptop', 
			'ddipocket', 		' droid',		'htc_dream', 
			'htc espresso', 	'htc hero', 		'htc halo', 
			'htc huangshan', 	'htc legend',		'htc liberty', 
			'htc paradise', 	'htc supersonic', 	'htc tattoo', 
			'ipad', 		'ipod', 		'iphone',
			'kindle', 		'kobo', 		'lge-cx', 
			'lge-lx', 		'lge-mx', 		'lge vx', 
			'lg;lx', 		'nexus', 		'nintendo wii',
			'nokia', 		'nook', 		'palm', 
			'pdxgw', 		'playstation', 		'sagem', 
			'samsung', 		'sec-sgh', 		'sharp',
			'sonyericsson', 	'sprint', 		'j-phone', 
			'milestone', 		'n410', 		'mot 24', 
			'mot-', 		'htc-', 		'htc_',  
			'htc ', 		'lge ', 		'lge-', 
			'sec-', 		'sie-m', 		'sie-s', 
			'spv ',			'smartphone', 		'midp', 
			'mobilephone', 		'wp', 			'zunehd', 
			'zune'  
		);
	
		// --------------------------------------------------------------
		// note: linux alone can't be searched for, and almost all linux 
		// devices are armv types ipad 'cpu os' is how the real os number 
		// is handled : could be an arm laptop, don't use 'linux armv'
		// note: the new windows 10 phone os ua has 'android' in it for 
		// some reason, so it goes first. Mozilla/5.0 (Windows Phone 10.0; 
		// Android 4.2.1; DEVICE INFO) AppleWebKit/537.36 (KHTML, like 
		// Gecko) Chrome/42.0.2311.135 Mobile Safari/537.36 Edge/12.0
		// --------------------------------------------------------------
		$a_mobile_os = array( 
			'windows phone os', 	'windows phone', 	'android', 
			'blackberry', 		'epoc', 		'cpu os', 
			'iphone os', 		'palmos', 		'palmsource', 
			'windows ce',		'symbianos', 		'symbian os', 
			'symbian', 		'webos'  
		);

		// ---------------------------------------------------------------
		// sometimes there is just no other id for the unit that the CTS 
		// type service/server 
		// ---------------------------------------------------------------
		$a_mobile_server = array( 
			'astel', 		'docomo', 		'novarra-vision', 
			'portalmmm', 		'reqwirelessweb', 	'vodafone' 
		);

		// ---------------------------------------------------------------
		// basic tablet detection. Note, android 3 was a tablet only 
		// release, android 4 is mobile/tablet. gt-p is samsung galaxy 
		// tablet (eg, gt-p = gt-p1000); verizon galaxy: SCH-I(xxx) sm-t 
		// is SM-T211 galaxy tab 3. Highly unreliable user agents though.
		// note: android 4 is a special case, and is only a tablet if the 
		// word 'mobile' is NOT in the string. Rather than loop through 
		// everything we'll test this manually below and only run the loop 
		// if not found.
		// NOTE that silk can only be tested for AFTER it's determined it's 
		// an android device, changed below to kindle
		// https://amazonsilk.wordpress.com/useful-bits/silk-user-agent/
		// cros: X11; CrOS armv7l 6680.81.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36
		// ' sch-i' is not accurate for tablets
		// ---------------------------------------------------------------
		$a_mobile_tablet = array( 
			'ipad', 		'android 3', 		'cros', 
			' gt-p', 		'sm-t', 		'kindle', 
			'kobo', 		'nook', 		'playbook', 
			'silk', 		'touchpad', 		'tablet', 
			'xoom' 
		);

		$k_count = count( $a_mobile_browser );
		for($k = 0; $k < $k_count; $k++ ) 
		{
			if(strstr($pv_browser_user_agent, $a_mobile_browser[$k])) 
			{
				$mobile_browser = $a_mobile_browser[$k];

				// ---------------------------------------------
				// this may or may not work, highly unreliable 
				// because mobile ua strings are random	
				// ---------------------------------------------
				$mobile_browser_number = get_item_version( 
					$pv_browser_user_agent, $mobile_browser );
				break;
			}
		}

		$k_count = count( $a_mobile_os );
		for($k = 0; $k < $k_count; $k++ ) 
		{
			if(strstr($pv_browser_user_agent, $a_mobile_os[$k]))
			{
				$mobile_os = $a_mobile_os[$k];
				if($mobile_os != 'blackberry')
				{
					// ----------------------------------------------
					// this may or may not work, highly unreliable
					// ----------------------------------------------
					$mobile_os_number = str_replace('_', '.', 
						$this->get_item_version($pv_browser_user_agent, $mobile_os));
				}
				else 
				{
					$mobile_os_number = str_replace('_', '.', 
						$this->get_item_version($pv_browser_user_agent, 'version'));

					// -----------------------------------------------
					// eg: BlackBerry9000/5.0.0.93 Profile/M.... 
					// -----------------------------------------------
					if(empty( $mobile_os_number))
					{
						$this->get_set_count('set', 5);
						$mobile_os_number = str_replace('_', '.', 
							$this->get_item_version($pv_browser_user_agent, $mobile_os));
                                	}
				}
				break;
			}
		}

		$k_count = count($a_mobile_server);
		for($k = 0; $k < $k_count; $k++)
		{
			if(strstr($pv_browser_user_agent, $a_mobile_server[$k]))
			{
				$mobile_server = $a_mobile_server[$k];
				
				// -----------------------------------------------
				// this may or may not work, highly unreliable
				// -----------------------------------------------
				$mobile_server_number = $this->get_item_version(
					$pv_browser_user_agent, $mobile_server);
                        	break;
                	}
		}

		// ------------------------------------------------------------
		// special case, google isn't showing tablet in the UA, but if 
		// it does not say 'mobile' in the ua, the device is tablet. 
		// This will probably change over time since mobile ua's are not 
		// settled. using regex (?!mobile) did not work in my tests, not 
		// sure why.
		// -------------------------------------------------------------
		$pattern = '/android[[:space:]]*[4-9]/';
		if(preg_match($pattern, $pv_browser_user_agent) && !stristr($pv_browser_user_agent, 'mobile'))
		{
			$mobile_tablet = 'android tablet';
		}
		else 
		{
			$k_count = count($a_mobile_tablet);
			for($k = 0; $k < $k_count; $k++)
			{
				if(strstr($pv_browser_user_agent, $a_mobile_tablet[$k]))
				{
					$mobile_tablet = trim($a_mobile_tablet[$k]);
					if($mobile_tablet == 'gt-p' 
					|| $mobile_tablet == 'sch-i' 
					|| $mobile_tablet == 'sm-t' ) 
					{
						$mobile_tablet = 'galaxy-' . $mobile_tablet;
					}
					elseif($mobile_tablet == 'silk')
					{
						$mobile_tablet = 'kindle fire';
					}
					break;
				}
			}
		}

		// -------------------------------------------------------------
		// just for cases where we know it's a mobile device already
		// -------------------------------------------------------------
		if(!$mobile_os
		&&($mobile_browser || $mobile_device || $mobile_server)
		&& strstr($pv_browser_user_agent, 'linux'))
		{
			$mobile_os = 'linux';
			$mobile_os_number = $this->get_item_version($pv_browser_user_agent, 'linux');
		}

		$a_mobile_data = array( 
			$mobile_device, 	$mobile_browser, 	$mobile_browser_number, 
			$mobile_os, 		$mobile_os_number, 	$mobile_server, 
			$mobile_server_number, 	$mobile_device_number, 	$mobile_tablet 
		);
		
		return $a_mobile_data;
	}

	// --------------------------------------------------------------------

	protected function get_html_level($pv_render_engine, $pv_render_engine_nu)
	{
	        $html_return = 1;
	        $engine_nu = $pv_render_engine_nu;

		// -------------------------------------------------------
		// Until further notice, this is the primary comparison 
		// table/data used for determining browser support: 
		// http://en.wikipedia.org/wiki/Comparison_of_layout_engines_%28HTML5%29
		// 
		// array holding start of browser support types.
		// note; gecko/webkit we know about, trident is msie >= 8 , 
		// presto opera >= 10 trident numbers are msie 8 or more 
		// number - 4; presto is just what it is for that release
		// these are all multiplied by ten to avoid locale math/decimal 
		// errors below
		// http://w3c-test.org/html/tests/harness/harness.htm
		//
		// NOTE: presto numbers went from 2.8 to 2.12, so you can't use this method, set to 20
        	// ------------------------------------------------------------
		$a_html5_basic = array(
			'blink' => 10,
			'edgehtml' => 4,
			'gecko' => 20,
			'khtml' => 45,
			'presto' => 20, // 26
			'trident' => 50,
			'webkit' => 5250
		);

		$a_html5_forms = array(
			'blink' => 10,
			'edgehtml' => 4,
			'gecko' => 20,
			'khtml' => 50,
			'presto' => 20, // 28
			'trident' => 60,
			'webkit' => 5280
		);

		// -------------------------------------------------------
		// floatval is not locale aware, so it will spit out a . 
		// type decimal separator but php says that internally it 
		// should work fine as intended, ie, locale agnostic
		// floatval/locales: https://bugs.php.net/bug.php?id=40653
		// --------------------------------------------------------
		$engine_nu = intval( 10 * floatval( $engine_nu ) );

		if ( array_key_exists( $pv_render_engine, $a_html5_forms )
		&& $a_html5_forms[$pv_render_engine] <= $engine_nu ) 
		{
			$html_return = 3;
		}
		elseif ( array_key_exists( $pv_render_engine, $a_html5_basic )
		&& $a_html5_basic[$pv_render_engine] <= $engine_nu ) 
		{
			$html_return = 2;
		}

		return $html_return;
	}

	// --------------------------------------------------------------------
	// --------------------------------------------------------------------
	// --------------------------------------------------------------------
	// --------------------------------------------------------------------
	// --------------------------------------------------------------------

	public function script_time()
	{
		// -----------------------------------------
		// NOTE: That microtime(true) requires PHP 5
		//       or greater for microtime
		// -----------------------------------------
		if(sprintf("%01.1f", phpversion()) >= 5)
		{
			if(is_null($this->script_time))
			{
				$this->script_time = microtime(true);
			}
			else
			{
				// -----------------------------------------
				// NOTE: (string)$var is same as strval($var)
				// -----------------------------------------
				$elapsed_time = (string)(microtime(true) - $this->script_time);
				$elapsed_time = sprintf("%01.8f", $elapsed_time);
				$this->script_time = NULL;
				return $elapsed_time;
			}
		}
	}

	// -------------------------------------------------
}

/* End of File: UserAgentParse.php */
/* Location: .application/libraries/UserAgentParse.php */
