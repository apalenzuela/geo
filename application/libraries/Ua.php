<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ua
{
	private $user_agent = '';

	private $browser  	= array(
		'name'		=> '',
		'version'	=> '',
		'number'	=> '',
	);

	private $engine   	= array(
		'name'		=> '',
		'version'	=> '',
		'number'	=> ''
	);

	private $platform 	= array(
		'name'		=> '',
		'number'	=> ''
	);

	private $os 		= array(
		'type'		=> '',
		'number'	=> '',
	);

	private $mobile   	= array(
		'device'	=> '',
		'number'	=> '',
		'server'	=> '',
		'srv_num'	=> '',
		'tablet'	=> '',
	);

	private $ua_type  	 	= '';
	private $html_type   		= '';

	private $elapse_time 		= 0;
	private $slice_increment	= 0;

	private $browser_types 	= array(
		array( 'baidu', 			false, 'baidu', 		'bot' ),
		array( 'bingbot', 			false, 'bing', 			'bot' ),
		array( 'bingpreview', 			false, 'bing', 			'bot' ),
		array( 'msnbot', 			false, 'msn', 			'bot' ),
		array( 'exabot', 			false, 'exabot', 		'bot' ),
		array( 'googlebot', 			false, 'google', 		'bot' ),
		array( 'google web preview', 		false, 'googlewp', 		'bot' ),
		array( 'yandex', 			false, 'yandex', 		'bot' ),
		array( 'edge', 				true,  'edge', 			'bro' ),
		array( 'msie', 				true,  'ie', 			'bro' ),
		array( 'trident', 			true,  'ie', 			'bro' ),
		array( 'blink', 			true,  'blink', 		'bro' ),
		array( 'opr/', 				true,  'blink', 		'bro' ),
		array( 'vivaldi', 			true,  'blink', 		'bro' ),
		array( 'webkit', 			true,  'webkit', 		'bro' ),
		array( 'opera', 			true,  'op', 			'bro' ),
		array( 'khtml', 			true,  'khtml', 		'bro' ),
		array( 'gecko', 			true,  'moz', 			'bro' ),
		array( 'netpositive', 			false, 'netp', 			'bbro' ),
		array( 'lynx', 				false, 'lynx', 			'bbro' ),
		array( 'elinks ', 			false, 'elinks', 		'bbro' ),
		array( 'elinks', 			false, 'elinks', 		'bbro' ),
		array( 'links2', 			false, 'links2', 		'bbro' ),
		array( 'links ', 			false, 'links', 		'bbro' ),
		array( 'links', 			false, 'links', 		'bbro' ),
		array( 'w3m', 				false, 'w3m', 			'bbro' ),
		array( 'webtv', 			false, 'webtv', 		'bbro' ),
		array( 'amaya', 			false, 'amaya', 		'bbro' ),
		array( 'dillo', 			false, 'dillo', 		'bbro' ),
		array( 'ibrowse', 			false, 'ibrowse', 		'bbro' ),
		array( 'icab', 				false, 'icab', 			'bro' ),
		array( 'crazy browser', 		true,  'ie', 			'bro' ),
		array( 'adsbot-google', 		false, 'google-ads', 		'bot' ),
		array( 'answerbus', 			false, 'answerbus', 		'bot' ),
		array( 'almaden', 			false, 'ibm', 			'bot' ),
		array( 'ask jeeves', 			false, 'ask', 			'bot' ),
		array( 'teoma', 			false, 'ask', 			'bot' ),
		array( 'bpimagewalker', 		false, 'bp-imagewalker', 	'bot' ),
		array( 'bhcbot', 			false, 'bhcbot', 		'bot' ),
		array( 'boitho.com-dc', 		false, 'boitho', 		'bot' ),
		array( 'comodospider', 			false, 'comodospider', 		'bot' ),
		array( 'domainsigmacrawler', 		false, 'domainsigmacrawler', 	'bot' ),
		array( 'dotbot', 			false, 'dotbot', 		'bot' ),
		array( 'downnotifier', 			false, 'downnotifier', 		'bot' ),
		array( 'facebookexternalhit', 		false, 'facebook', 		'bot' ),
		array( 'facebot', 			false, 'facebook', 		'bot' ),
		array( 'fast-webcrawler', 		false, 'fast', 			'bot' ),
		array( 'fatbot', 			false, 'fatbot', 		'bot' ),
		array( 'gigabot', 			false, 'gigabot', 		'bot' ),
		array( 'googledocs', 			false, 'google-docs', 		'bot' ),
		array( 'gozaikbot', 			false, 'gozaikbot', 		'bot' ),
		array( 'headmasterseo', 		false, 'headmasterseo', 	'bot' ),
		array( 'hosttracker', 			false, 'hosttracker', 		'bot' ),
		array( 'hybridbot', 			false, 'hybridbot', 		'bot' ),
		array( 'ia_archiver', 			false, 'ia_archiver', 		'bot' ),
		array( 'icc-crawler', 			false, 'icc-crawler', 		'bot' ),
		array( 'iltrovatore-setaccio', 		false, 'il-set', 		'bot' ),
		array( 'imagewalker', 			false, 'imagewalker', 		'bot' ),
		array( 'lexxebotr', 			false, 'lexxebotr', 		'bot' ),
		array( 'linkscanner', 			false, 'linkscanner', 		'bot' ),
		array( 'linkwalker', 			false, 'linkwalker', 		'bot' ),
		array( 'magpie-crawler', 		false, 'magpie-crawler', 	'bot' ),
		array( 'mediapartners-google', 		false, 'adsense', 		'bot' ),
		array( 'mj12bot', 			false, 'mj12bot', 		'bot' ),
		array( 'naverbot', 			false, 'naverbot', 		'bot' ),
		array( 'objectssearch', 		false, 'objectsearch', 		'bot' ),
		array( 'omgilibot', 			false, 'omgilibot', 		'bot' ),
		array( 'openbot', 			false, 'openbot', 		'bot' ),
		array( 'pinterest', 			false, 'pinterest', 		'bot' ),		
		array( 'primalbot', 			false, 'primalbot', 		'bot' ),
		array( 'psbot', 			false, 'psbot', 		'bot' ),
		array( 'redback', 			false, 'redback', 		'bot' ),
		array( 'scooter', 			false, 'scooter', 		'bot' ),
		array( 'seoscanners', 			false, 'seoscanners', 		'bot' ),
		array( 'slackbot', 			false, 'slackbot', 		'bot' ),
		array( 'slack.com', 			false, 'slackbot', 		'bot' ),
		array( 'sogou', 			false, 'sogou', 		'bot' ),
		array( 'sosospider', 			false, 'sosospider', 		'bot' ),
		array( 'sohu-search', 			false, 'sohu', 			'bot' ),
		array( 'surdotlybot', 			false, 'surdotlybot', 		'bot' ),
		array( 'surveybot', 			false, 'surveybot', 		'bot' ),
		array( 'switescraper', 			false, 'switescraper', 		'bot' ),
		array( 'uxcrawlerbot', 			false, 'uxcrawlerbot', 		'bot' ),
		array( 'vbseo', 			false, 'vbseo', 		'bot' ),
		array( 'vegi bot', 			false, 'vegi-bot', 		'bot' ),
		array( 'xenu', 				false, 'xenu', 			'bot' ),
		array( 'yahoo link preview', 		false, 'yahoo-preview', 	'bot' ),
		array( 'yahoo-verticalcrawler', 	false, 'yahoo', 		'bot' ),
		array( 'yahoo! slurp', 			false, 'yahoo', 		'bot' ),
		array( 'slurp', 			false, 'inktomi', 		'bot' ),
		array( 'inktomi', 			false, 'inktomi', 		'bot' ),
		array( 'yahoo-mm', 			false, 'yahoomm', 		'bot' ),
		array( 'zyborg', 			false, 'looksmart', 		'bot' ),
		array( 'ahc', 				false, 'ahc', 			'lib' ),
		array( 'w3c_validator', 		false, 'w3c', 			'lib' ),
		array( 'wdg_validator', 		false, 'wdg', 			'lib' ), 
		array( 'libwww-perl', 			false, 'libwww-perl', 		'lib' ),
		array( 'jakarta commons-httpclient', 	false, 'jakarta', 		'lib' ),
		array( 'java', 				false, 'java', 			'lib' ),
		array( 'okhttp', 			false, 'okhttp', 		'lib' ),
		array( 'python-urllib', 		false, 'python-urllib', 	'lib' ),
		array( 'ruby', 				false, 'ruby', 			'lib' ),
		array( 'winhttp', 			false, 'winhttp', 		'lib' ),
		array( 'curl', 				false, 'curl', 			'dow' ),
		array( 'favicon downloader', 		false, 'favicon-downloader', 	'dow' ),
		array( 'guzzle', 			false, 'guzzle', 		'dow' ), 
		array( 'getright', 			false, 'getright', 		'dow' ),
		array( 'wget', 				false, 'wget', 			'dow' ),
		array( 'mozilla/4.', 			false, 'ns', 			'bbro' ),
		array( 'mozilla/3.', 			false, 'ns', 			'bbro' ),
		array( 'mozilla/2.', 			false, 'ns', 			'bbro' )
	);

	private $blink_types 	= array(
		'opr/', 			'vivaldi', 			'chromium', 
		'chrome', 			'blink'
	);

	private $gecko_types 	= array( 
		'bonecho', 			'camino', 			'conkeror', 
		'epiphany', 			'fennec', 			'firebird',
		'flock', 			'galeon', 			'iceape', 
		'icecat', 			'k-meleon', 			'minimo', 
		'multizilla', 			'phoenix',			'skyfire', 
		'songbird', 			'swiftfox', 			'seamonkey', 
		'shadowfox', 			'shiretoko', 			'iceweasel',
		'firefox', 			'minefield', 			'netscape6', 
		'netscape', 			'rv' 
	);

	private $khtml_types 	= array( 
		'konqueror', 			'khtml' 
	);

	private $trident_types 	= array( 
		'ucbrowser', 			'ucweb', 			'msie' 
	);

	private $webkit_types 	= array( 
		'arora', 			'bolt', 			'beamrise', 
		'chromium', 			'puffin', 			'chrome',
		'crios', 			'dooble', 			'epiphany', 
		'gtklauncher', 			'icab', 			'konqueror', 
		'maxthon',  			'midori',			'omniweb', 
		'opera', 			'qupzilla', 			'rekonq', 
		'rocketmelt', 			'samsungbrowser', 		'silk', 
		'uzbl',				'ucbrowser', 			'ucweb', 
		'shiira', 			'sputnik', 			'steel', 
		'teashark', 			'safari',  			'applewebkit', 
		'webos', 			'xxxterm',			'vivaldi', 
		'yabrowser', 			'webkit'
	);

	private $mobile_browser = array( 
		'avantgo', 			'blazer', 			'crios', 
		'elaine', 			'eudoraweb',			'fennec', 
		'iemobile',  			'minimo', 			'ucweb', 
		'ucbrowser', 			'mobile safari', 		'mobileexplorer',
		'opera mobi', 			'opera mini', 			'netfront', 
		'opwv', 			'polaris', 			'puffin', 
		'samsungbrowser', 		'semc-browser', 		'silk', 
		'steel', 			'ultralight', 			'up.browser', 
		'webos', 			'webpro/', 			'wms pie', 
		'xiino' 
	);

	private $mobile_device	= array( 
		'benq', 			'blackberry', 			'danger hiptop', 
		'ddipocket', 			' droid',			'htc_dream', 
		'htc espresso', 		'htc hero', 			'htc halo', 
		'htc huangshan', 		'htc legend',			'htc liberty', 
		'htc paradise', 		'htc supersonic', 		'htc tattoo', 
		'ipad', 			'ipod', 'iphone',		'kindle', 
		'kobo', 			'lge-cx', 			'lge-lx',        'lg-',
		'lge-mx', 			'lge vx', 			'lg;lx', 
		'nexus', 			'nintendo wii',			'nokia', 
		'nook', 			'palm', 			'pdxgw', 
		'playstation', 			'sagem', 			'samsung', 
		'sec-sgh', 			'sharp',			'sonyericsson', 
		'sprint', 			'j-phone', 			'milestone', 
		'n410', 			'mot 24', 			'mot-',
		'htc-', 			'htc_',  			'htc ', 
		'lge ', 			'lge-', 			'sec-', 
		'sie-m', 			'sie-s', 			'spv ',
		'smartphone', 			'midp', 			'mobilephone', 
		'wp', 				'zunehd', 			'zune'
	);

	private $mobile_os 		= array( 
		'windows phone os', 		'windows phone', 		'android', 
		'blackberry',			'epoc', 			'cpu os', 
		'iphone os', 			'palmos', 			'palmsource', 
		'windows ce', 			'symbianos', 			'symbian os', 
		'symbian', 			'webos'  
	);

	private $mobile_server 	= array( 
		'astel', 			'docomo', 			'novarra-vision', 
		'portalmmm', 			'reqwirelessweb', 		'vodafone' 
	);

	private $mobile_tablet 	= array( 
		'ipad', 			'android 3',			'cros', 
		' gt-p', 			'sm-t',				'kindle', 
		'kobo', 			'nook', 			'playbook', 
		'silk', 			'touchpad', 			'tablet', 
		'xoom' 
	);

	private $mobile_search = array(
		'android', 			'blackberry', 			'epoc', 
		'palmos', 			'palmsource', 			'windows ce',
		'windows phone os', 		'windows phone', 		'symbianos', 
		'symbian os', 			'symbian', 			'webos', 
		/** devices - ipod before iphone or fails **/
		'benq', 			'blackberry', 			'danger hiptop', 
		'ddipocket', 			' droid', 			'ipad', 
		'ipod', 			'iphone',			'kindle', 
		'kobo', 			'lge-cx', 			'lge-lx', 
		'lge-mx', 			'lge vx', 			'lge ', 
		'lge-', 			'lg;lx',			'nexus', 
		'nintendo wii', 		'nokia', 			'nook', 
		'palm', 			'pdxgw', 			'playstation', 
		'rim', 				'sagem', 			'samsung', 
		'sec-sgh', 			'sharp', 			'sonyericsson', 
		'sprint', 			'zune', 			'j-phone',
		'n410', 			'mot 24', 			'mot-', 
		'htc-', 			'htc_', 			'htc ', 
		'playbook', 			'sec-', 			'sie-m', 
		'sie-s', 			'spv ',				'touchpad', 
		'vodaphone', 			'smartphone', 			'midp', 
		'mobilephone',
		/** browsers **/
		'avantgo', 			'blazer', 			'elaine', 
		'eudoraweb', 			'fennec', 			'iemobile',  
		'minimo',			'mobile safari', 		'mobileexplorer', 
		'opera mobi', 			'opera mini', 			'netfront', 
		'opwv',				'polaris', 			'puffin', 
		'samsungbrowser', 		'semc-browser', 		'skyfire', 
		'up.browser',			'ucweb', 			'ucbrowser', 
		'webpro/', 			'wms pie', 			'xiino',
		/** services - astel out of business **/
		'astel', 			'docomo', 			'novarra-vision', 
		'portalmmm', 			'reqwirelessweb', 		'vodafone'
	);

	// --------------------------------------------------------------------

	public function __construct($ua = '')
	{
		$this->set($ua);
	}

	// --------------------------------------------------------------------

	public function parse($ua)
	{
		$CI =& get_instance();
		$CI->load->model(array('Useragent'));

		$b_success = false;
		$this->set($ua);
		
		$this->script_time();

		if(empty($ua))
		{
			return '';
		}

		$browser_working 	= '';
		$layout_engine 		= '';
		$layout_engine_nu_full	= '';
		$layout_engine_nu	= '';
		$blink_type		= null;
		$khtml_type		= null;
		$moz_type		= null;
		$trident_type		= null;
		$webkit_type		= null;

		foreach($this->browser_types as $browser_type)
		{
			$browser_temp = $browser_type[0];

			if(strstr($this->user_agent, $browser_temp))
			{
				$b_safe_browser 	= true;
				$browser_name 		= $browser_temp;
				$b_dom_browser 		= $browser_type[1];
				$browser_working 	= $browser_type[2];
				$this->ua_type 		= $browser_type[3];

				switch ( $browser_working ) 
				{
					case 'ns':
						$b_safe_browser = false;
						$browser_number = $this->get_item_version('mozilla');
						break;
					case 'blink':
						if($browser_name == 'opr/')
						{
							$this->get_set_count('set', 0 );
						}

						$browser_number = $this->get_item_version($browser_name);
						$layout_engine = 'blink';
						if (strstr($this->user_agent, 'blink'))
						{
							$layout_engine_nu_full = $this->get_item_version('blink');
						}
						else 
						{
							$layout_engine_nu_full = $this->get_item_version('webkit');
						}

						$layout_engine_nu = $this->get_item_math_number($browser_number);

						foreach($this->blink_types as $bt)
						{
							if(strstr($this->user_agent, $bt))
							{
								$blink_type = $bt;

								if($browser_name == 'opr/')
								{
									$this->get_set_count('set', 0);
								}

								$blink_type_number = $this->get_item_version($blink_type);
								$browser_name      = $bt;

								if($browser_name == 'opr/')
								{
									$this->get_set_count('set', 0);
								}

								$browser_number = $this->get_item_version($browser_name);
							}
						}

						if ( $browser_name == 'opr/' )
						{
							$browser_name = 'opera';
						}
						break;
					case 'dillo':
						$browser_number = $this->get_item_version($browser_name);
						$layout_engine = 'dillo';
						$layout_engine_nu = $this->get_item_math_number($browser_number);
						$layout_engine_nu_full = $browser_number;
						break;
					case 'edge':
						$browser_number = $this->get_item_version($browser_name);
						$layout_engine = 'edgehtml';
						$layout_engine_nu = $this->get_item_math_number($browser_number);
						$layout_engine_nu_full = $browser_number;
						break;
					case 'khtml':
						$browser_number = $this->get_item_version($browser_name);
						$layout_engine = 'khtml';
						$layout_engine_nu = $this->get_item_math_number($browser_number);
						$layout_engine_nu_full = $browser_number;
						foreach($this->khtml_types as $kt)
						{
							if(strstr($this->user_agent, $kt))
							{
								$khtml_type = $kt;
								$khtml_type_number = $this->get_item_version($khtml_type);
								$browser_name = $kt;
								$browser_number = $this->get_item_version($browser_name);
								break;
							}
						}
						break;
					case 'moz':
						$this->get_set_count('set', 0);
						$moz_rv_full = $this->get_item_version('rv:');
						$moz_rv = floatval( $moz_rv_full );
						foreach($this->gecko_types as $gt)
						{
							if(strstr($this->user_agent, $gt))
							{
								$moz_type = $gt;
								$moz_type_number = $this->get_item_version($moz_type);
								break;
							}
						}

						if(!$moz_rv) 
						{
							$moz_rv = floatval($moz_type_number);
							$moz_rv_full = $moz_type_number;
						}

						if($moz_type == 'rv')
						{
							$moz_type = 'mozilla';
						}

						$browser_number = $moz_rv;

						$this->get_set_count('set', 0);
						$moz_release_date = $this->get_item_version('gecko/');
						
						$layout_engine = 'gecko';
						$layout_engine_nu = $moz_rv;
						$layout_engine_nu_full = $moz_rv_full;

						if(($moz_release_date < 20020400) 
						|| ($moz_rv < 1 ))
						{
							$b_safe_browser = false;
						}
						break;
					case 'ie':
						$b_gecko_ua = false;
						if(strstr($this->user_agent, 'rv:'))
						{
							$browser_name = 'msie';
							$b_gecko_ua = true;
							$this->get_set_count('set', 0);

							$browser_number = $this->get_item_version('rv:', '', '' );
						}
						else 
						{
							$browser_number = $this->get_item_version($browser_name, true, 
												'trident/' );
						}

						$this->get_set_count('set', 0);

						$layout_engine_nu_full = $this->get_item_version('trident/', '', '' );
						
						if($layout_engine_nu_full)
						{
							$layout_engine_nu = $this->get_item_math_number(
													 $layout_engine_nu_full);
							$layout_engine = 'trident';

							if(strstr($browser_number, '7.' ) 
							&& !$b_gecko_ua)
							{
								$true_ie_number = $this->get_item_math_number( $browser_number ) + (intval($layout_engine_nu ) - 3);
							}
							else 
							{
								$true_ie_number = $browser_number;
							}

							foreach($this->trident_types as $tt)
							{
								if(strstr($this->user_agent, $tt))
								{
									$trident_type = $tt;
									$trident_type_number = $this->get_item_version($trident_type);
									break;
								}
							}

							if ( !$trident_type && $b_gecko_ua ) 
							{
								$trident_type = 'msie';
								$trident_type_number = $browser_number;
							}
						}
						elseif(intval($browser_number) <= 7 
						&& intval($browser_number) >= 4)
						{
							$layout_engine = 'trident';
							if(intval($browser_number) == 7)
							{
								$layout_engine_nu_full = '3.1';
								$layout_engine_nu = '3.1';
							}
						}

						if($browser_number >= 9) 
						{
							$ie_version = 'ie9x';
						}
						elseif($browser_number >= 7)
						{
							$ie_version = 'ie7x';
						}
						elseif(strstr($this->user_agent, 'mac'))
						{
							$ie_version = 'ieMac';
						}
						elseif($browser_number >= 5)
						{
							$ie_version = 'ie5x';
						}
						elseif(($browser_number > 3) 
						&& ($browser_number < 5))
						{
							$b_dom_browser = false;
							$ie_version = 'ie4';
							$b_safe_browser = true;
						}
						else 
						{
							$ie_version = 'old';
							$b_dom_browser = false;
							$b_safe_browser = false;
						}
						break;
					case 'op':
						if($browser_name == 'opr/')
						{
							$browser_name = 'opr';
						}

						$browser_number = $this->get_item_version($browser_name);
						if(strstr( $browser_number, '9.') 
						&& strstr( $browser_user_agent, 'version/'))
						{
							$this->get_set_count('set', 0);
							$browser_number = $this->get_item_version('version/');
						}

						$this->get_set_count('set', 0);
						$layout_engine_nu_full = $this->get_item_version('presto/');
						if($layout_engine_nu_full) 
						{
							$layout_engine = 'presto';
							$layout_engine_nu = $this->get_item_math_number(
													$layout_engine_nu_full );
						}

						if(!$layout_engine_nu_full 
						&& $browser_name == 'opr')
						{
							if(strstr($this->user_agent, 'blink'))
							{
								$layout_engine_nu_full = $this->get_item_version('blink');
							}
							else 
							{
								$layout_engine_nu_full = $this->get_item_version('webkit');
							}
							$layout_engine_nu = $this->get_item_math_number(
													 $layout_engine_nu_full);
							
							$layout_engine = 'blink';
							$browser_name = 'opera';
						}

						if($browser_number < 5)
						{
							$b_safe_browser = false;
						}
						
						break;
					case 'webkit':
						$browser_number = $this->get_item_version($browser_name);
						$layout_engine = 'webkit';
						$layout_engine_nu = $this->get_item_math_number($browser_number);
						$layout_engine_nu_full = $browser_number;
						foreach($this->webkit_types as $wt)
						{
							if(strstr($this->user_agent, $wt))
							{
								$webkit_type = $wt;

								if($webkit_type == 'omniweb')
								{
									$this->get_set_count('set', 2);
								}

								$webkit_type_number = $this->get_item_version($webkit_type);
								
								if($wt == 'gtklauncher')
								{
									$browser_name = 'epiphany';
								}
								else 
								{
									$browser_name = $wt;
								}

								if(($wt == 'chrome' || $wt == 'chromium') 
								&& $this->get_item_math_number($webkit_type_number) >= 28)
								{
									if(strstr($this->user_agent, 'blink'))
									{
										$layout_engine_nu_full = $this->get_item_version('blink');
										$layout_engine_nu = $this->get_item_math_number($layout_engine_nu_full);
									}

									$layout_engine = 'blink';
								}
								$browser_number = $this->get_item_version($browser_name );
								break;
							}
						}
						break;
					default:
						$browser_number = $this->get_item_version($browser_name);
						break;
				}
				$b_success = true;
				break;
			}
		}

		if(!$b_success)
		{
			$browser_name = substr($this->user_agent, 0, strcspn( $this->user_agent , '();'));

			if($browser_name 
			&& preg_match( '/[^0-9][a-z]*-*\ *[a-z]*\ *[a-z]*/', $browser_name, $a_unhandled_browser)) 
			{
				$browser_name = $a_unhandled_browser[0];
				
				if($browser_name == 'blackberry')
				{
					$this->get_set_count('set', 0);
				}

				$browser_number = $this->get_item_version($browser_name);
			}
			else 
			{
				$browser_name = 'NA';
				$browser_number = 'NA';
			}
		}

		$a_os_data = $this->get_os_data($browser_name, $browser_number);
		$os_type   = $a_os_data[0];
		$os_number = $a_os_data[1];
		$os_name   = $a_os_data[2];

		$b_repeat = true;

		$browser_math_number = $this->get_item_math_number($browser_number);
		
		$this->engine['name'] 			= $layout_engine;
		$this->engine['version'] 		= $layout_engine_nu_full;
		$this->engine['number']			= $layout_engine_nu;

		$this->os['type']			= $os_type;
		$this->os['distro']			= $os_number;
		$this->os['name']			= $os_name;
		$this->os['platform']			= $this->get_platform();

		$this->html_type			= $this->get_html_level($layout_engine, $layout_engine_nu);

		if($blink_type != '')
		{
			$this->browser['name']		= $blink_type;
			$this->browser['version']	= $blink_type_number;
			$this->browser['number']	= $browser_number;
		}

		if($khtml_type != '')
		{
			$this->browser['name']		= $khtml_type;
			$this->browser['version']	= $khtml_type_number;
			$this->browser['number']	= $browser_number;
		}

		if($moz_type != '')
		{
			$this->browser['name']		= $moz_type;
			$this->browser['version']	= $moz_type_number;
			$this->browser['number']	= $moz_rv;
			$this->browser['full']		= $moz_rv_full;
			$this->browser['release']	= $moz_release_date;
		}

		if($trident_type != '')
		{
			$this->browser['name']		= $trident_type;
			$this->browser['version']	= $trident_type_number;
			$this->browser['number']	= $browser_number;
		}

		if($webkit_type != '')
		{
			$this->browser['name']		= $webkit_type;
			$this->browser['version']	= $webkit_type_number;
			$this->browser['number']    	= $browser_number;
		}

                $mobile_test = $this->check_is_mobile( $this->user_agent);
                if(!empty($mobile_test))
                {       
                        $this->get_mobile_data($this->user_agent);
                        $this->ua_type = 'mobile';
			$this->mobile['name'] = $mobile_test;
                }

		switch($this->ua_type)
		{
			case 'bot':
				$this->ua_type = 'robot, spider';
				break;
			case 'bro':
				$this->ua_type = 'browser';
				break;
                	case 'bbro':
				$this->ua_type = 'basic browser';
				break;
			case 'dow':
				$this->ua_type = 'download agent';
				break;
			case 'lib':
				$this->ua_type = "standard http libraries";
				break;
		}

                // ---------------------------------------------------
                // EXTRA
                // ---------------------------------------------------
                if(($row = $CI->Useragent->retrieve_by_user_agent(str_replace('+', '', $this->user_agent))) != FALSE)
                {
                        $browser_types = $CI->config->item('browser_types');
                        $this->browser['comment'] 	= $row->comment;
                        $this->browser['description']	= $row->description;
			$this->browser['link_1']	= $row->link1;
			$this->browser['link_2']	= $row->link2;
			$this->browser['version']	= $browser_number;
			$this->browser['name']		= $browser_name;
			
			$types = array();
			if(!empty($row->{'type'}))
			{
				$parts = explode(' ', $row->{'type'});
				foreach($parts as $part)
				{
					if(isset($browser_types[$part]))
					{
						$types[] = "[".$browser_types[$part]."]";
					}
				}
			}

			if(count($types) > 0)
			{
				$this->browser['types'] = implode(',', $types);
				$this->ua_type          = implode(',', $types);
			}
                }
		// -----------------------------------------------

		$data = array(
			'engine'	=> $this->engine,
			'os'		=> $this->os,
			'browser'	=> $this->browser,
			'ua_type'	=> $this->ua_type,
			'html_type'	=> $this->html_type,
			'delay'		=> $this->script_time(),
		);

		if(!empty($mobile_test))
		{
			$data['mobile']	= $this->mobile;
		}

		// ---------------------------------------------------
                
		$crawler_detection = $this->crawler_detect();

                if($crawler_detection['is_crawler'] == true)
                {
                        $data['ua_type'] = 'crawler';
			$data['crawler'] = $crawler_detection['name'];
                }

		return $data;
	}

	// --------------------------------------------------------------------

	public function set($ua = '')
	{
		if(!empty($ua))
		{
			$this->user_agent = strtolower($ua);
		}
		elseif(isset($_SERVER['HTTP_USER_AGENT']))
		{
			$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		}
		else
		{
			$this->user_agent = '';
		}
	}

	// --------------------------------------------------------------------

	public function get()
	{
		return $this->user_agent;
	}

	// --------------------------------------------------------------------

	private function get_item_math_number($str_with_number)
	{
		$math_number = '';
		if(!empty($str_with_number)
		&& preg_match('/^[0-9]*\.*[0-9]*/', $str_with_number, $results))
		{
			$math_number = $results[0];
		}

		return $math_number;
	}

	// --------------------------------------------------------------------

	private function get_os_data($browser_name, $version_number)
	{
		$os_working_type 	= '';
		$os_working_name	= '';
		$os_working_number 	= '';

		$arr_mac 		= array(
			'intel mac', 		'OS X', 		'ppc mac', 
			'mac68k'
		);

		$arr_unix_types	= array( 
			'dragonfly', 		'freebsd', 		'openbsd', 
			'netbsd', 		'bsd', 			'unixware',
			'solaris', 		'sunos', 		'sun4', 
			'sun5', 		'suni86', 		'sun', 
			'irix5', 		'irix6', 		'irix',
			'hpux9', 		'hpux10', 		'hpux11', 
			'hpux', 		'hp-ux', 		'aix1', 
			'aix2', 		'aix3', 		'aix4', 
			'aix5', 		'aix',			'sco', 
			'unixware', 		'mpras', 		'reliant', 
			'dec', 			'sinix', 		'unix' 
		);

		$arr_linux_distros = array( 
			' cros ', 		'ubuntu', 		'kubuntu', 
			'xubuntu', 		'mepis', 		'xandros',
			'linspire', 		'winspire', 		'jolicloud', 
			'sidux', 		'kanotix', 		'debian', 
			'opensuse', 		'suse',			'fedora', 
			'redhat', 		'slackware', 		'slax', 
			'mandrake', 		'mandriva', 		'gentoo', 
			'sabayon',		'linux'
		);

		$arr_linux_process = array(
			'i386', 		'i586', 		'i686', 
			'x86_64'
		);

		$arr_os_types = array( 
			'blackberry', 		'iphone', 		'palmos', 
			'palmsource', 		'symbian', 		'beos', 
			'os2',			'amiga', 		'webtv', 
			'macintosh', 		'mac_', 		'mac ', 
			'nt', 			'win',			'android',
			$arr_unix_types, 	$arr_linux_distros 
		);

		foreach($arr_os_types as $pos => $os_type)
		{
			if(!is_array($os_type)
			&& strstr($this->user_agent, $os_type)
			&& !strstr($this->user_agent, 'linux'))
			{
				$os_working_type = $os_type;
				switch ($os_type) 
				{
					case 'nt':
						preg_match('/nt ([0-9]+[\.]?[0-9]?)/', $this->user_agent, $arr_matches);
						if(isset($arr_matches[1])) 
						{
							$os_working_number = $arr_matches[1];
							$os_working_name   = "Windows NT";
						}
						break;
					case 'win':
						/** windows vista, for opera ID **/
						if(strstr($this->user_agent, 'vista'))
						{
							$os_working_number = 6.0;
							$os_working_name   = "Windows Vista";
							$os_working_type   = 'nt';
						}
						/** windows xp, for opera ID **/
						elseif(strstr($this->user_agent, 'xp'))
						{
							$os_working_number = 5.1;
							$os_working_name   = "Windows XP";
							$os_working_type   = 'nt';
						}
						/** windows server 2003, for opera ID **/
						elseif(strstr($this->user_agent, '2003'))
						{
							$os_working_number = 5.2;
							$os_working_name   = "Windows 2003";
							$os_working_type   = 'nt';
						}
						/** windows CE **/
						elseif(strstr($this->user_agent, 'windows ce'))
						{
							$os_working_number = 'ce';
							$os_working_name   = "Windows CE";
							$os_working_type   = 'nt';
						}
						elseif(strstr($this->user_agent, '95'))
						{
							$os_working_name   = 'Windows 95';
							$os_working_number = '95';
						}
						elseif((strstr($this->user_agent, '9x 4.9')) 
					    	|| (strstr($this->user_agent, ' me')))
					    	{
							$os_working_name   = "Windows ME";
					     		$os_working_number = 'me';
					    	}
					    	elseif(strstr($this->user_agent, '98'))
					    	{
							$os_working_name   = 'Windows 98';
							$os_working_number = '98';
						}
						/** windows 2000, for opera ID **/
						elseif(strstr($this->user_agent, '2000'))
						{
							$os_working_number = 5.0;
							$os_working_name   = "Windows 2000";
							$os_working_type   = 'nt';
						}
						break;
					case 'mac ':
					case 'mac_':
					case 'macintosh':
						$os_working_type = 'mac';

						if(strstr($this->user_agent, 'os x'))
						{
							/** if it doesn't have a version number, it is os x; **/
							if(strstr($this->user_agent, 'os x '))
							{
								/** numbers are like: 10_2.4, others 10.2.4 **/
								$os_working_number = str_replace('_', '.', 
									$this->get_item_version($this->user_agent, 'os x'));
							}
							else 
							{
								$os_working_number = 10;
							}
						}
						/**
							this is a crude test for os x, since safari, 
							camino, ie 5.2, & moz >= rv 1.3
							are only made for os x
						**/
						elseif($browser_name == 'saf'
							|| $browser_name == 'cam'
							|| (($browser_name == 'moz') && ($version_number >= 1.3))
							|| (($browser_name == 'ie' ) && ($version_number >= 5.2)))
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
				break;
			}
			/**
				check that it's an array, check it's the 
				second to last item in the main os array, 
				the unix one that is
			**/
			elseif(is_array($os_type) && ($pos == (count($arr_os_types) - 2)))
			{
				foreach($os_type as $unix_type)
				{
					if(strstr($this->user_agent, $unix_type))
					{
						/** if the os is in the unix array, it's unix, obviously... **/
						$os_working_type = 'Unix'; 

						/** assign sub unix version from the unix array **/
						$os_working_number = ($unix_type != 'unix') 
											? $unix_type 
											: '';
						break;
					}
				}
			}
			/**
				check that it's an array, check it's the 
				last item in the main os array, the linux 
				one that is
			**/
			elseif(is_array($os_type) && ($pos == (count($arr_os_types) - 1)))
			{
				foreach($os_type as $linux_type)
				{
					if(strstr($this->user_agent, $linux_type))
					{
						$os_working_type = 'Linux';
						/**
							assign linux distro from the linux 
							array, there's a default search for 
							'lin', if it's that, set version to ''
						**/
						$os_working_number = ($linux_type != 'linux' ) 
											? $linux_type 
											: '';
						break;
					}
				}
			}
		}

		/** 
			pack the os data array for 
			return to main function 
		**/
		return array(
			$os_working_type,
			$os_working_number,
			$os_working_name
		);
	}

	// --------------------------------------------------------------------

	private function get_item_version($search_string, $break_last = '', $extra_search = '')
	{
		if(empty($search_string) || empty($this->user_agent))
		{
			return '';
		}

		$substring_length			= 15;
		$start_pos				= 0;
		$string_working_number 	= '';

		for($i = 0; $i < 4; $i++)
		{
			if(strpos($this->user_agent, $search_string, $start_pos) !== false)
			{
				$start_pos = strpos($this->user_agent, $search_string, $start_pos) + 
								strlen($search_string);

				if(!$break_last 
				|| ($extra_search && strstr($this->user_agent, $extra_search)))
				{
					break;
				}
			}
			else
			{
				break;
			}
		}

		$start_pos += $this->get_set_count('get');
		$string_working_number = substr($this->user_agent, $start_pos, $substring_length);

		// $string_working_number = substr($string_working_number, 0, 
		// 	strcspn($string_working_number, " );/+", 0, $substring_length));

		preg_match('!\d+(?:\.\d+)?!', $string_working_number, $matches);

		if(count($matches) > 0)
		{
			$string_working_number = $matches[0];
		}

		if(!is_numeric(substr($string_working_number, 0, 1)))
		{
			$string_working_number = '';
		}

		return $string_working_number;
	}

	// --------------------------------------------------------------------

	private function get_set_count($pv_type, $pv_value = '')
	{
		$return_value = '';
		switch ($pv_type) 
		{
			case 'get':
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

	private function check_is_mobile()
	{
		if(empty($this->user_agent))
		{
			return '';
		}

		$mobile_working_test = '';
		foreach($this->mobile_search as $m_search)
		{
			if(strstr($this->user_agent, $m_search))
			{
				if($m_search != 'zune' || strstr($this->user_agent, 'iemobile'))
				{
					$mobile_working_test = $m_search;
					break;
				}
			}
		}

		return $mobile_working_test;
	}

	// --------------------------------------------------------------------

	private function get_mobile_data()
	{
		if(empty($this->user_agent))
		{
			return;
		}

		foreach($this->mobile_browser as $m_browser)
		{
			if(strstr($this->user_agent, $m_browser))
			{
				$this->browser['name']   = $m_browser;
				$this->browser['number'] = $this->get_item_version($m_browser);
				break;
			}
		}

		foreach($this->mobile_device as $m_device)
		{
			if(strstr($this->user_agent, $m_device))
			{
				$this->mobile['device'] = trim($m_device, '-_');
				if($this->mobile['device'] == 'blackberry')
				{
					$this->get_set_count('set', 0);
				} 

				$this->mobile['number'] = $this->get_item_version($this->mobile['device']);
				$this->mobile['device'] = trim($this->mobile['device']);
				break;
			}
		}

		foreach($this->mobile_os as $m_os)
		{
			if(strstr($this->user_agent, $m_os))
			{
				$this->os['name'] = $m_os;
				if($this->os['name'] != 'blackberry')
				{
					$this->os['number'] = str_replace('_', '.', 
						$this->get_item_version($this->os['name']));
				}
				else // is blackberry
				{
					$this->os['number'] = str_replace('_', '.', 'version');
					if(empty($this->os['number']))
					{
						$this->get_set_count('set', 5);
						$this->os['number'] = str_replace('_', '.', 
							$this->get_item_version($this->os['name']));
					}
				}
			}
			break;
		}	

		foreach($this->mobile_server as $m_server)
		{
			if(strstr($this->user_agent, $m_server))
			{
				$this->mobile['server']  = $m_server;
				$this->mobile['srv_num'] = $this->get_item_version($this->mobile['server']);
				break;
			}
		}

		$pattern = '/android[[:space]]*[4-9]/';
		if(preg_match($pattern, $this->user_agent)
		&& !stristr($this->user_agent, 'mobile'))
		{
			$this->mobile['tablet'] = 'android tablet';
		}
		else
		{
			foreach($this->mobile_tablet as $m_tablet)
			{
				if(strstr($this->user_agent, $m_tablet))
				{
					$this->mobile['tablet'] = trim($m_tablet);

					if($this->mobile['tablet'] == 'gt-p' 
					|| $this->mobile['tablet'] == 'sch-i'
					|| $this->mobile['tablet'] == 'sm-t')
					{
						$this->mobile['tablet'] = 'galaxy-'.$this->mobile['tablet'];
					}
					elseif($this->mobile['tablet'] == 'silk')
					{
						$this->mobile['tablet'] = 'kindle fire';
					}
					break;
				}
			}
		}

		if(empty($this->os['name']) 
		&& (!empty($this->browser['name']) 
		 || !empty($this->mobile['device']) 
		 || !empty($this->mobile['server']))
		&& strstr($this->user_agent, 'linux'))
		{
			$this->os['name']   = 'linux';
			$this->os['number'] = $this->get_item_version('linux');
		}
	}

	// --------------------------------------------------------------------

	private function get_html_level($pv_render_engine, $pv_render_engine_nu)
	{
		$html_return = 1;
		$engine_nu = $pv_render_engine_nu;

		$html5_basic = array(
			'blink' 		=> 10,
			'edgehtml' 		=> 4,
			'gecko' 		=> 20,
			'khtml' 		=> 45,
			'presto' 		=> 20, // 26
			'trident' 		=> 50,
			'webkit' 		=> 5250
		);

		$html5_forms = array(
			'blink' 		=> 10,
			'edgehtml' 		=> 4,
			'gecko' 		=> 20,
			'khtml' 		=> 50,
			'presto' 		=> 20, // 28
			'trident' 		=> 60,
			'webkit' 		=> 5280	
		);

		$engine_nu = intval(10 * floatval($engine_nu));

		if(array_key_exists($pv_render_engine, $html5_forms)
		&& $html5_forms[$pv_render_engine] <= $engine_nu)
		{
			$html_return = 3;
		}
		elseif(array_key_exists($pv_render_engine, $html5_basic) 
		&& $html5_basic[$pv_render_engine] <= $engine_nu)
		{
			$html_return = 2;
		}

		return $html_return;
	}

	// --------------------------------------------------------------------

	private function get_platform()
	{
		if(empty($this->user_agent))
		{
			return;
		}

		$platform = '';
		if( preg_match('/\((.*?)\)/im', $this->user_agent, $parent_matches))
		{
			preg_match_all('/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|iPod|Linux|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|X11|(New\ )?Nintendo\ (WiiU?|3?DS)|Xbox(\ One)?)
				(?:\ [^;]*)?
				(?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);
			$priority = array( 'Xbox One', 'Xbox', 'Windows Phone', 'Tizen', 'Android', 'CrOS', 'X11' );
			$result['platform'] = array_unique($result['platform']);
		
			if( count($result['platform']) > 1 ) 
			{
				if( $keys = array_intersect($priority, $result['platform']) ) 
				{
					$platform = reset($keys);
				} 
				else 
				{
					$platform = $result['platform'][0];
				}
			} 
			elseif( isset($result['platform'][0]) ) 
			{
				$platform = $result['platform'][0];
			}
		}

		if( $platform == 'linux-gnu' || $platform == 'X11' || $platform == 'x11') 
		{
			$platform = 'Linux';
		} 
		elseif( $platform == 'CrOS' ) 
		{
			$platform = 'Chrome OS';
		}

		return $platform;
	}

	// --------------------------------------------------------------------

	private function crawler_detect()
	{
		if(empty($this->user_agent))
		{
			return '';
		}

		$crawlers = array(
			'Google' 		=> 'Google',
//			'GoogleBot'		=> 'GoogleBot',
			'MSN' 			=> 'msnbot', 
			'Rambler' 		=> 'Rambler',
			'Yahoo' 		=> 'Yahoo',
			'AbachoBOT' 		=> 'AbachoBOT',
			'accoona' 		=> 'Accoona',
			'AcoiRobot' 		=> 'AcoiRobot',
			'ASPSeek' 		=> 'ASPSeek',
			'CrocCrawler' 		=> 'CrocCrawler',
			'Dumbot' 		=> 'Dumbot',
			'FAST-WebCrawler' 	=> 'FAST-WebCrawler',
			'GeonaBot' 		=> 'GeonaBot',
			'Gigabot' 		=> 'Gigabot',
			'Lycos spider' 		=> 'Lycos',
			'MSRBOT' 		=> 'MSRBOT',
			'Altavista robot' 	=> 'Scooter',
			'AltaVista robot' 	=> 'Altavista',
			'ID-Search Bot' 	=> 'IDBot',
			'eStyle Bot' 		=> 'eStyle',
			'Scrubby robot' 	=> 'Scrubby',
			'Facebook' 		=> 'facebookexternalhit',
  		);

		// to get crawlers string used in function uncomment it
		// it is better to save it in string than use implode every time
		// global $crawlers 
   		$crawlers_agents = implode('|',$crawlers);
  		if (strpos($crawlers_agents, $this->user_agent) === false)
      		{	
			return array('is_crawler' => false);
    		}
		else 
		{ 
			$crawler_name = '';
			foreach($crawlers as $crawKey => $crawler)
			{
				if(strpos($this->user_agent, $crawler) !== FALSE)
				{
					$crawler_name = $crawKey;
				}
			}
			return array('is_crawler' => TRUE, 'name' => $crawler_name);
    		}   
	}

	// ---------------------------------------------------------------------

	private function script_time()
	{
		if(sprintf("%01.1f", phpversion()) >= 5)
		{
			if($this->elapse_time == 0)
			{
				$this->elapse_time = microtime(true);
			}
			else
			{
				$time = (microtime(true) - $this->elapse_time);
				$time = sprintf("%01.8f", $time);
				$this->elapse_time = 0;

				return $time;
			}
		}
	}

	// --------------------------------------------------------------------
}

/* End of file ua.php */
/* Location: .application/libraries/ua.php */
