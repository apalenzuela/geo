<?php defined('BASEPATH') OR exit("No direct script access allowed");

/**
 * @class Ip2location
 * @author Alain Palenzuela
 * @parent MY_Model
 * @access public 
**/
class Ip2location extends MY_Model
{
	// ----------------------------------------------
	// Constructor
	// ----------------------------------------------
	public function __construct()
	{
		parent::__construct();	

		$this->table = strtolower(get_class($this));
		$this->primary_key = 'zip_code';
	}

	// ----------------------------------------------

	/**
	 * Function: get_by_zip
	 *
	 * this function return the record for the zip
	 * code if was found, if not and error is trigged
	 *
	 * @access public
	 * @param  string zip_code
	 * @return Result/boolean
	 *
	**/
	public function get_by_zip($_zip_code = '', $_country = '')
	{
		if(empty($_zip_code))
		{
			TriggerException(CODE_10, __FILE__, __LINE__);
		}

		if(!empty($_country))
		{
			$this->db->where('country_code', strtoupper($_country));
		}

		$query = $this->db->like('zip_code', $_zip_code, 'none')
				  ->get($this->table);

		log_debug($this->db->last_query());

		if($query && $query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			TriggerException(CODE_9, __FILE__, __LINE__);
		}
	}

	// ----------------------------------------------
	
	/**
	 * Function: get_by_ip 
	 *
 	 * this function will return all the entries
	 * where the ip could be
	 *
	 * @access public
	 * @param  long
	 * @return ResultSet/boolean
	 *
	 ***/
	public function get_by_ip($_ip = '')
	{
		if(empty($_ip))
		{
			TriggerException(CODE_10, __FILE__, __LINE__);
		}

		if(@inet_pton($_ip) == false)
		{
			TriggerException(CODE_10, __FILE__, __LINE__);
		}

		$_ip = ip2long($_ip);
		$query = $this->db->where('ip_from <', $_ip)
				  ->where('ip_to >', $_ip)
				  ->get($this->table);

		log_debug($this->db->last_query());

		if($query && $query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			TriggerException(CODE_9, __FILE__, __LINE__);
		}
	}

	// ----------------------------------------------
	// ----------------------------------------------
	// ----------------------------------------------
	// ----------------------------------------------
	// ----------------------------------------------
	// ----------------------------------------------
}

/*
mysql> desc ip2location;
+--------------+------------------+------+-----+---------+-------+
| Field        | Type             | Null | Key | Default | Extra |
+--------------+------------------+------+-----+---------+-------+
| ip_from      | int(10) unsigned | YES  | MUL | NULL    |       |
| ip_to        | int(10) unsigned | YES  | MUL | NULL    |       |
| country_code | char(2)          | YES  |     | NULL    |       |
| country_name | varchar(64)      | YES  |     | NULL    |       |
| region_name  | varchar(128)     | YES  |     | NULL    |       |
| city_name    | varchar(128)     | YES  |     | NULL    |       |
| latitude     | double           | YES  |     | NULL    |       |
| longitude    | double           | YES  |     | NULL    |       |
| zip_code     | varchar(30)      | YES  | MUL | NULL    |       |
| time_zone    | varchar(8)       | YES  |     | NULL    |       |
+--------------+------------------+------+-----+---------+-------+
10 rows in set (0.00 sec)

*/

/* End of file: Ip2location.php */
/* Location: .application/models/Ip2location.php */
