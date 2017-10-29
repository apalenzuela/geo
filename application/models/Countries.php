<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @class Countries
 * @author Alain Palenzuela
 * @parent MY_Model
 * @access public
**/
class Countries extends MY_Model
{
	// ---------------------------------------------
	// Constructor
	// ---------------------------------------------
	public function __construct()
	{
		parent::__construct();

		$this->table = strtolower(get_class($this));
		$this->primary_key = 'id';
	}

	// ---------------------------------------------
	// ---------------------------------------------
	// ---------------------------------------------
	// ---------------------------------------------
	// ---------------------------------------------
	// ---------------------------------------------
}

/* End of file: Countries.php */
/* Location: .application/models/Countries.php */
