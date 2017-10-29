<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * This class uses the openssl function to encrypt
 * and decrypt data using private and public keys
 */
class Lock
{
	private static $locks    = 1;
	private static $lockname = 'apilock';
	private static $timeout  = 100;
	private static $db       = null;

    // ------------------------------------------------------

	/**
	 * Function: get
	 *
	 * This funciton return the name of the lock on MySQL
	 * if that lock is free, or FALSE if any of the possible
	 * lock is available
	 *
	 * @access public
	 * @static
	 * @param  integer
	 * @return string|boolean
	 */
	public static function get($_locks = 1)
	{
		// --------------------------------------------------
		// Is the parameter is passed, then the new amount is
		// accepted as the amount of possible locks
		// --------------------------------------------------
		self::$locks = $_locks;

		// --------------------------------------------------
		// If is the first time, (is static then always is the
		// first time)
		// --------------------------------------------------
		if(is_null(self::$db))
		{
			self::get_db();
		}

		// ---------------------------------------------------
		// loop for the amount of locks we will to accept at
		// a time
		// ---------------------------------------------------
		for($lock = 0; $lock < self::$locks; $lock++)
		{
			$_lockname = self::$lockname.$lock;
			$_timeout  = self::$timeout;

			// echo "Release: {$_lockname}: ".(self::release("apilock{$lock}") ? 'T' : 'F')."<br />";

			if(self::is_free("{$_lockname}"))
			{
				$sql   = "SELECT GET_LOCK('{$_lockname}', {$_timeout}) AS locked";
				$query = self::$db->query($sql);

				$locked = ($query && $query->num_rows() > 0) ? $query->row()->locked : FALSE;

				if($locked != FALSE)
				{
					// echo "Get '{$_lockname}' <br/>";
					return $_lockname; // $locked;
				}
			}
		}

		return FALSE;
	}

	// ------------------------------------------------------

	/**
	 * Function: release
	 *
	 * this function release one lock if the lock is really
	 * locked, return TRUE if was release or never was lock
	 *
	 * @access public
	 * @static
	 * @param  string
	 * @return boolean
	 */
	public static function release($_lockname)
	{
		if(empty($_lockname))
		{
			return FALSE;
		}

		// --------------------------------------------------
		// If is the first time, (is static then always is the
		// first time)
		// --------------------------------------------------
		if(is_null(self::$db))
		{
			self::get_db();
		}

		if(self::is_in_use($_lockname))
		{
			$sql   = "SELECT RELEASE_LOCK('{$_lockname}') AS released";
			$query = self::$db->query($sql);

			$return = ($query && $query->num_rows() > 0) 
					? (($query->row()->released == '1') ? TRUE : FALSE)
					: FALSE; 
			// echo "Released '{$_lockname}': ".($return ? 'Yes' : 'No')."<br/>";

			return $return;
		}

		return TRUE;
	}

	// ------------------------------------------------------

	public function release_all($_locks = 1)
	{
		// --------------------------------------------------
		// Is the parameter is passed, then the new amount is
		// accepted as the amount of possible locks
		// --------------------------------------------------
		self::$locks = $_locks;

		// --------------------------------------------------
		// If is the first time, (is static then always is the
		// first time)
		// --------------------------------------------------
		if(is_null(self::$db))
		{
			self::get_db();
		}

		// --------------------------------------------------
		// List all
		// --------------------------------------------------
		for($lock = 0; $lock < self::$locks; $lock++)
		{
			$_lockname = self::$lockname.$lock;
			self::release($_lockname);
		}
	}

	// ------------------------------------------------------

	/**
	 * Function: is_used
	 *
	 * This function return TRUE is this lock number is in
	 * use, FALSE if not
	 * 
	 * @access private
	 * @param  none
	 * @return none
	 */
	private function is_in_use($_lockname)
	{
		// ----------------------------------------------
		// Check if this lock is in use
		// ----------------------------------------------
		$sql   = "SELECT IS_USED_LOCK('{$_lockname}') AS is_used";
		$query = self::$db->query($sql);

		$return = ($query && $query->num_rows() > 0)
				? ($query->row()->is_used != NULL ? TRUE : FALSE)
				: FALSE;

		// echo "IsUsed '{$_lockname}': ".($return ? 'Yes' : 'No')."<br/>";

		return $return;
	}

	// ------------------------------------------------------

	/**
	 * Function: is_free
	 *
	 * this function check if the lock is free or not
	 *
	 * @access private
	 * @param  string
	 * @return boolean
	 */
	private function is_free($_lockname)
	{
		// -----------------------------------------------------
		// Check if this lock, is locked
		// -----------------------------------------------------
		$sql   = "SELECT IS_FREE_LOCK('{$_lockname}') AS is_free";
		$query = self::$db->query($sql);

		$return = ($query && $query->num_rows() > 0) 
				? ($query->row()->is_free == '1' ? TRUE : FALSE) 
				: FALSE;

		// echo "IsFree '{$_lockname}': ".($return ? 'Yes' : 'No')." <br/>";
		return $return;
	}

	// ------------------------------------------------------

	/**
	 * Function: list
	 *
	 * This function return an echo for all LOCK status
	 * 
	 * @access public
	 * @param  integer
	 * @return none
	 */
	public static function lists($_locks = 1)
	{
		// --------------------------------------------------
		// Is the parameter is passed, then the new amount is
		// accepted as the amount of possible locks
		// --------------------------------------------------
		self::$locks = $_locks;

		// --------------------------------------------------
		// If is the first time, (is static then always is the
		// first time)
		// --------------------------------------------------
		if(is_null(self::$db))
		{
			self::get_db();
		}

		// --------------------------------------------------
		// List all
		// --------------------------------------------------
		for($lock = 0; $lock < self::$locks; $lock++)
		{
			$_lockname = self::$lockname.$lock;
			echo "Entry '{$_lockname}': ".(self::is_in_use($_lockname) ? '++++' : '----')."<br/>";
		}
	}

	// ------------------------------------------------------

	/**
	 * Function: get_db
	 *
	 * this function set up the field db with an instance
	 * of the database access
	 *
	 * @access private
	 * @param  none
	 * @return none
	 */
	private function get_db()
	{
		$CI =& get_instance();
		$CI->load->database();

		self::$db = $CI->db;
	}

    // ------------------------------------------------------
}

/* End of file lock.php */
/* Location: ./application/libraries/lock.php */
