<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller 
{
        // ------------------------------------

        public function __construct()
        {
                parent::__construct();
        }

	// ------------------------------------

	public function index()
	{
		// -------------------------------------
		// Variable Initialization
		// -------------------------------------
		$this->load->library('Answer');

		$this->answer->setTag('geo');
		$this->answer->showHeader(TRUE);
	
		$responses = array();
		
		// -------------------------------------
		// Starting the logic
		// -------------------------------------
		try
		{
			// -------------------------------------------
			// Prepare the entry into the logs - debug
			// -------------------------------------------
			$str_length = 70;
			$msg_length = 55;
			$half       = intval(($str_length - $msg_length) / 2);

			log_debug(str_repeat('*', $str_length)); 
			log_debug(str_repeat('*', $half)."   __ _  ____  _  _    ____  __ _  ____  ____  _  _    ".str_repeat('*', $half));
			log_debug(str_repeat('*', $half)."  (  ( \(  __)/ )( \  (  __)(  ( \(_  _)(  _ \( \/ )   ".str_repeat('*', $half));
			log_debug(str_repeat('*', $half)."  /    / ) _) \ /\ /   ) _) /    /  )(   )   / )  /    ".str_repeat('*', $half));
			log_debug(str_repeat('*', $half)."  \_)__)(____)(_/\_)  (____)\_)__) (__) (__\_)(__/     ".str_repeat('*', $half));
			log_debug(str_repeat('*', $half).str_repeat(' ', $msg_length).str_repeat('*', $half));
			log_debug(str_repeat('*', $str_length));
			log_debug('Environment: '.ENVIRONMENT);

			// -------------------------------------------
			// Recovering GET Values
			// -------------------------------------------
			$map = get_url_map();
			$this->load->library('Params', $map);

			log_debug("Initial GET Parameters: ", serialize($map));
			// -------------------------------------------
			// Recover the command
			// -------------------------------------------
			if(($command = $this->params->get_command()) == FALSE)
			{
				TriggerException(CODE_8, __FILE__, __LINE__);
			}
			else
			{
				log_debug("Command executed: {$command}");
				switch($command)
				{
					case CMD_ZIP:
						$xml_headers = TRUE;
						$responses[] = $this->_search_zip($this->params);
						break;
					case CMD_IP:
						$xml_headers = TRUE;
						$responses[] = $this->_search_ip($this->params);
						break;
					case CMD_AGENT:
						$xml_headers = TRUE;
						$responses[] = $this->_user_agent($this->params);
						break;
					case CMD_REQUEST:
						$xml_headers = FALSE;
						$responses[] = $this->_request($this->params);
						break;
					case CMD_IMPORT:
						$xml_headers = TRUE;
						$responses[] = $this->_import($this->params);
						break;
					default: 
						TriggerException(CODE_8, __FILE__, __LINE__);
						break;
				}
			}
		}
		// -------------------------------------------
		// Process the exception
		// -------------------------------------------
		catch(Exception $e)
		{
			$exception = json_decode($e->getMessage(), true);
			if(!is_array($exception))
			{
				$exception = array(
					EX_CODE 	=> CODE_6,
					EX_MSG		=> MSG_6
				);

				log_message('ERROR', $e->getMessage());
				log_debug($e->getMessage());
			}

			$responses[] = new Answer('error', array(
				new Answer(EX_CODE, $exception[EX_CODE]),
				new Answer(EX_MSG,  $exception[EX_MSG])
			));
		}

		// ----------------------------------------------
		// Prepare the answer
		// ----------------------------------------------
		$this->answer->addValue($responses);
		$this->answer->showHeader(true);

		// ----------------------------------------------
		// Recover the array of headers
		// ----------------------------------------------
		$headers = $this->config->item('headers');

		// ----------------------------------------------
		// Recover and validate the format of the output
		// ----------------------------------------------
                $format  = in_array($this->params->{Params::PRM_OUTPUT}, array_keys($headers))
                         ? $this->params->{Params::PRM_OUTPUT}
                         : DFLT_OUTPUT;

		// ----------------------------------------------
		// Prepare the answer and validate with the use
		// of the SWITCH of possible formats
		// ----------------------------------------------
		$data['answer'] = "";
		switch($format)
		{
			case RST_JSON: 
				$data['answer'] = $this->answer->asJSON();
				break;
			case RST_XML:
			default:
				$data['answer'] = $this->answer->asXML();
				break;
		}
	
		// ---------------------------------------------
		// Send the headers
		// ---------------------------------------------
		foreach($headers[$format] as $header)
		{
			log_debug("Header: {$header}");
			header($header);
		}

		// ---------------------------------------------
		// Send the answer
		// ---------------------------------------------
		$this->load->view('default', $data);
	}

	// ------------------------------------------------------

	/**
	 * Function: import
	 *
	 * This function import
 	 *
	 * @access privated
	 * @param  none
	 * @return nothing
	 *
	 ********/
	private function _import($params = array())
	{
		$this->load->model(array('Useragent'));

		$filename = '/var/www/html/geo/html/user_agents.xml';

		if(file_exists($filename) && is_readable($filename))
		{
			$xml = simplexml_load_string(file_get_contents($filename));

			if($xml && get_class($xml) == 'SimpleXMLElement')
			{
				$batch   = array();
				foreach($xml->children() as $child)
				{
					$row = array(
						'identifier'	=> (string)$child->ID,
						'user_agent'	=> (string)$child->{'String'},
						'description'	=> (string)$child->Description,
						'type'		=> (string)$child->{'Type'},
						'comment'	=> (string)$child->Comment,
						'link1'		=> (string)$child->Link1,
						'link2'		=> (string)$child->Link2
					);

					if(($exist_row = $this->Useragent->retrieve_by_user_agent($row['user_agent'])) == FALSE)
					{
						$batch[] = $row;
					}

					if(count($batch) > 10)
					{
						$this->Useragent->insert_batch($batch);
						$batch = array();
					}
				}
			}

			return new Answer('Imported', 'Yes');
		}

		TriggerException(CODE_9, __FILE__, __LINE__);
	}

	// ------------------------------------------------------

	/**
	 * Function: _request
	 *
	 * this function process all request received
	 *
	 * @access privated
	 * @param  params
	 * @return nothing
	 *
	*******/
	private function _request($params = array())
	{
		$this->load->helper('files');

		$request = $params->{Params::PRM_REQUEST};

		if(empty($request))
		{
			exit(0);
		}

		$filename = APPPATH."logs/entries.txt";
		$result = f_write($filename, $request);		

		exit(0);
	}

	// ------------------------------------------------------

	/**
	 * Function: _search_ip
	 *
	 *  Cmd: _search_ip. This function return the result of
	 *  search by ip into the DB
	 * 
	 * @access public
	 * @param  stdclass
	 * @return stdclass
	 */
	private function _search_ip($params = array())
	{
		$this->load->model(array('ip2location'));

		if(($rows = $this->ip2location->get_by_ip($params->{Params::PRM_IP})) != FALSE)
		{
                        $results = array();
                        $entries = array();
                        foreach($rows as $row)
                        {
                                $exists = FALSE;
                                foreach($entries as $result)
                                {
                                        if(($result->country_code == $row->country_code)
                                        && ($result->region_name == $row->region_name)
                                        && ($result->city_name == $row->city_name))
                                        {
                                                $exists = TRUE;
                                        }
                                }

                                if($exists == FALSE)
                                {
					$country_code = strtolower($row->country_code);
					$flag = "http://geo.alainpalenzuela.com/flags/24x24/{$country_code}.png";

					$google_map = "https://google.com/maps/@".
							"{$row->latitude},{$row->longitude},15z";

                                        $results[] = new Answer('entry', array(
                                                new Answer('country_code', $row->country_code),
                                                new Answer('country_name', $row->country_name),
                                                new Answer('region_name',  $row->region_name),
                                                new Answer('city_name',    $row->city_name),
                                                new Answer('latitude',     $row->latitude),
                                                new Answer('longitude',    $row->longitude),
                                                new Answer('zip_code',     $row->zip_code),
                                                new Answer('time_zone',    $row->time_zone),
						new Answer('flag',	   $flag),
						new Answer('google_map',   $google_map)
                                        ));

                                        $_entry = new stdClass();

                                        $_entry->country_code = $row->country_code;
                                        $_entry->region_name  = $row->region_name;
                                        $_entry->city_name    = $row->city_name;

                                        $entries[] = $_entry;
                                }
                        }

                        return new Answer('results', $results, array('rows' => count($results)));
			// vd::stop($rows);
		}
		else
		{
			TriggerException(CODE_8, __FILE__, __LINE__);
		}
	}

	// ---------------------------------------------

	/**
	 * Function: _search_zip
	 *
	 *  Cmd: _search_zip. This function return the result of
	 *  search by zip
	 * 
	 * @access public
	 * @param  stdclass
	 * @return stdclass
	 */
	private function _search_zip($params = array())
	{
		// ----------------------------------------
		// Load model
		// ----------------------------------------
		$this->load->model(array('ip2location'));

		// ----------------------------------------
		// Recover all rows (including duplicates)
		// ----------------------------------------
		if(($rows = $this->ip2location->get_by_zip($params->{Params::PRM_ZIP}, $params->{Params::PRM_COUNTRY})) != FALSE)
		{
			$results = array();	// Results
			$entries = array();	// Just to verify which record is on the results

			// ------------------------------------
			// Loops all rows
			// ------------------------------------
			foreach($rows as $row)
			{
				$exists = FALSE;
				foreach($entries as $result)
				{
					// -------------------------------------
					// Just verify if the rows is into the
					// result already
					// -------------------------------------
					if(($result->country_code == $row->country_code)
					&& ($result->region_name == $row->region_name)
					&& ($result->city_name == $row->city_name))
					{
						$exists = TRUE;
					}
				}

				// ---------------------------------------------
				// Is FALSE if the row is not into results array
				// ---------------------------------------------
				if($exists == FALSE)
				{
					// -------------------------------------------
					// Generate link to the flag image
					// -------------------------------------------
					$country_code = strtolower($row->country_code);
					$flag = "http://geo.alainpalenzuela.com/flags/24x24/{$country_code}.png";

					// -------------------------------------------
					// Generate link to google map
					// -------------------------------------------
					$google_map = "https://www.google.com/maps/place/{$row->zip_code}".
							"/@{$row->latitude},{$row->longitude},13z";

					// -------------------------------------------
					// Generate the record for this entry
					// ------------------------------------------
					$results[] = new Answer('entry', array(
						new Answer('country_code', $row->country_code),
						new Answer('country_name', $row->country_name),
						new Answer('region_name',  $row->region_name),
						new Answer('city_name',    $row->city_name),
						new Answer('latitude',     $row->latitude),
						new Answer('longitude',    $row->longitude),
						new Answer('zip_code',     $row->zip_code),
						new Answer('time_zone',    $row->time_zone),
						new Answer('flag',	   $flag),
						new Answer('google_map',   $google_map)
					));

					// ------------------------------------------
					// Generate a new entry for verification 
					// array
					// ------------------------------------------
                                        $_entry = new stdClass();

                                        $_entry->country_code = $row->country_code;
                                        $_entry->region_name  = $row->region_name;
                                        $_entry->city_name    = $row->city_name;

                                        $entries[] = $_entry;
				}
			}

			return new Answer('results', $results, array('rows' => count($results)));
		}
		// ---------------------------------------------
		// No rows recovered, then empty result
		// ---------------------------------------------
		else
		{
			TriggerException(CODE_4, __FILE__, __LINE__);
		}
	}

	// ---------------------------------------------------------------------------

	/**
	 * Function: _user_agent
	 *
	 * this function parse and return a few values
	 * from the User Agent received
	 *
	 * @access private
	 * @param  stdClass
	 * @return stdClass/Boolean
	 *
	***/
	private function _user_agent($params = array())
	{
		$user_agent = $params->{Params::PRM_AGENT};

		if(empty($user_agent))
		{
			TriggerException(CODE_10, __FILE__, __LINE__);
		}
		else
		{
			$this->load->library('Ua');

			$user_agent = base64_decode($user_agent);

			$ua = $this->ua->parse($user_agent);

			$results = array();
			$results[] = new Answer('user_agent', $user_agent);

			if(count($ua) > 0 && !empty($ua['engine']['name']) && !empty($ua['browser']['name']))
			{
				foreach($ua as $tag => $entries)
				{
					if(is_array($entries) && count($entries) > 0 )
					{
						$elements = array();
						foreach($entries as $sub_tag => $value)
						{
							if(!empty($value))
							{
								$elements[] = new Answer($sub_tag, $value);
							}
						}

						if(count($elements) > 0)
						{
							$results[] = new Answer($tag, $elements);
						}
					}
					else
					{
						$results[] = new Answer($tag, $entries);
					}
				}
			}
			else
			{
				$results = array();
				$results[] = new Answer('user_agent', "");
			}

			return new Answer('results', $results, array('rows' => count($results)));
		}
	}

	// Example: Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2pre) Gecko/20100116 Ubuntu/9.10 (karmic) Namoroka/3.6pre
	// Example: http://geo.alainpalenzuela.com/agent/TW96aWxsYS81LjAgKFgxMTsgVTsgTGludXggeDg2XzY0OyBlbi1VUzsgcnY6MS45LjJwcmUpIEdlY2tvLzIwMTAwMTE2IFVidW50dS85LjEwIChrYXJtaWMpIE5hbW9yb2thLzMuNnByZQ/format/json

	// Example2: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Trident/4.0; GTB6; SLCC1; .NET CLR 2.0.50727; OfficeLiveConnector.1.3; OfficeLivePatch.0.0; .NET CLR 3.5.30729; InfoPath.2; .NET CLR 3.0.30729; MSOffice 12)
	// Example2: http://geo.alainpalenzuela.com/agent/TW96aWxsYS80LjAgKGNvbXBhdGlibGU7IE1TSUUgNy4wOyBXaW5kb3dzIE5UIDYuMDsgVHJpZGVudC80LjA7IEdUQjY7IFNMQ0MxOyAuTkVUIENMUiAyLjAuNTA3Mjc7IE9mZmljZUxpdmVDb25uZWN0b3IuMS4zOyBPZmZpY2VMaXZlUGF0Y2guMC4wOyAuTkVUIENMUiAzLjUuMzA3Mjk7IEluZm9QYXRoLjI7IC5ORVQgQ0xSIDMuMC4zMDcyOTsgTVNPZmZpY2UgMTIp/format/json

	// Example3: Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2pre) Gecko/20100116 Ubuntu/9.10 (karmic) Namoroka/3.6pre
	// Example3: http://geo.alainpalenzuela.com/agent/TW96aWxsYS81LjAgKFgxMTsgVTsgTGludXggeDg2XzY0OyBlbi1VUzsgcnY6MS45LjJwcmUpIEdlY2tvLzIwMTAwMTE2IFVidW50dS85LjEwIChrYXJtaWMpIE5hbW9yb2thLzMuNnByZQ/format/json

	// Example4: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040803 Firefox/0.9.3
	// Example4: http://geo.alainpalenzuela.com/agent/TW96aWxsYS81LjAgKFdpbmRvd3M7IFU7IFdpbmRvd3MgTlQgNS4xOyBlbi1VUzsgcnY6MS43KSBHZWNrby8yMDA0MDgwMyBGaXJlZm94LzAuOS4z/format/json

	// ---------------------------------------------------------------------------
}

/* End of file: api.php */
/* Location: .application/controllers/api.php */
