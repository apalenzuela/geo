<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @class Useragent
 * @author Alain Palenzuela
 * @parent MY_Model
 * @access public
 *
 *****/
class Useragent extends MY_Model
{
	// --------------------------------------------
	// Constructor
	// --------------------------------------------
	public function __construct()
	{
		parent::__construct();

		$this->table = strtolower(get_class($this));
		$this->primary_key = 'identifier';
	}

	// ---------------------------------------------

	/**
	 * Function: retrieve_by_user_agent
	 *
	 * this function return the record, if found of the
	 * crawlers
	 *
	 * @access public
	 * @param  string user_agent
	 * @return stdclass/boolean
	 *
	*******/
	public function retrieve_by_user_agent($ua = '')
	{
		if(empty($ua))
		{
			return FALSE;
		}

		$query = $this->db->like('user_agent', $ua, 'both')
				  ->limit(1)
				  ->get($this->table);

		log_debug($this->db->last_query());

		return ($query && $query->num_rows() > 0) ? $query->row() : FALSE;  
	}

	// -----------------------------------------------
}

/* End of file: Useragent.php */
/* Location: .application/models/Useragent.php */
