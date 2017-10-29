<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class: MY_Model
 * Parent: CI_Model
 *
 * The models Booksnew and Magsnew should be
 * extended by this class MY_Model, because
 * the class share functions for both classes
 *
 * @class: MY_Model
 * @file: MY_Model.php
 * @extend CK_Model
 * @author: PHP Team
 * 
 */
class MY_Model extends CI_Model
{
    protected $db;
    protected $table;
    protected $primary_key = 'id';

    public    $debug   	   = FALSE;
    
    protected $user_data   = array();

    private   $fields 	   = array();

    // --------------------------------------------------------------------------------

    /**
     * Function: __construct()
     *
     * This is the constructor of the class.  
     */
    public function __construct()
    {
        // +---------------------------------------------------+
        // | Compatibility with the ansestors
        // +---------------------------------------------------+
        parent::__construct();

        // +---------------------------------------------------+
        // | Make a copy of the DB Drivers for this model
        // +---------------------------------------------------+
        $CI =& get_instance();
        $this->load->database();
        $this->db = $CI->db;
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: __toString()
     *
     * This will help the code with the Object Injection Technique
     *
     */
    public function __toString()
    {
        return get_class($this);
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: get_columns
     *
     * This function return the names of all fields
     * of the table.
     *
     * @access public
     * @param  none
     * @return array
     */
    public function get_columns()
    {
        $fields = array();
        if(!empty($this->table))
        {
            $fields = $this->db->list_fields($this->table);
        }

        return $fields;
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: exists
     *
     * the function return TRUE if at lease one
     * entry is found on the table, If NOT 
     * return FALSE
     *
     * @access public
     * @param  string 
     * @return boolean
     */
    public function exists( $key = '')
    {
        if(empty($key))
        {
            return FALSE;
        }

        $counter = $this->db->where($this->primary_key, $key)
                            ->from($this->table)
                            ->count_all_results();

        if($this->debug) 
        {
            log_debug($this->db->last_query());
        }

        return ($counter > 0) ? TRUE : FALSE;
    }

    // --------------------------------------------------------------------------------

    /**
     *  Function: getUniqueID
     *
     *  This function return a UNIQUE value
     *
     * @access public
     * @param  none
     * @return short|booleans
     */
    public function getUniqueID()
    {
        $query = $this->db->query('SELECT uuid_short() AS uuid');
        return ($query && $query->num_rows() > 0) ? $query->row()->uuid : FALSE;
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: setPK()
     *
     * if the developer want to changes the default primary
     * key, or use a diferent field as a primary key, this is
     * the way to do it.
     *
     * @access public
     * @param  string 
     * @return none
     */
    public function setPK( $pk = '' ) 
    {
	$this->set_pk($pk);
    }

    public function set_pk( $pk = '' )
    {
        if(!empty($pk))
        {
            $this->primary_key = $pk;
        }
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: getPK()
     *
     * This function return an string with the name of the 
     * field currently used as a primary key
     *
     * @access public
     * @param  none
     * @return string
     */
    public function getPK()
    {
        return $this->get_pk();
    }

    public function get_pk()
    {
        return $this->primary_key;
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: Insert
     *
     * this function make an simple INSERT command, it 
     * dont do any validation to try to insert the object
     *
     * @access public
     * @param  object|array
     * @return boolean
     */
    public function insert( $row = array() )
    {
        if(count((array)$row) == 0)
        {
            return FALSE;
        }

        $this->db->insert($this->table, $row);

        log_debug($this->db->last_query());
        
        return ($this->db->affected_rows() > 0);
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: insert_batch
     *
     * this function insert an amount of rows passed
     * on the function parameter
     *
     * @access public
     * @param  array
     * @return boolean
     */
    public function insert_batch($array = array())
    {
        if(count($array) == 0)
        {
            return FALSE;
        }

        $this->db->insert_batch($this->table, $array);

        log_debug($this->db->last_query());

        return ($this->db->affected_rows() > 0);
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: getInsertedID()
     *
     * return the ID for the inserted row
     *
     * @access public
     * @param  none
     * @return integer
     */
    public function getInsertedID()
    {
        return $this->db->insert_id();
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: Update
     *
     * This function make an update, for an specific 
     * 'primary key' row, to do multiple updates use
     * the update_all function
     *
     * @access public 
     * @param  string 
     * @param  stdclass|array
     * @return boolean
     */
    public function update( $pk = '', $row = array())
    {
	log_debug("Key: {$pk}");
	log_debug("Row: ", $row);
	
        if(count((array)$row) == 0 || empty($pk))
        {
            return FALSE;
        }

        $this->db->where($this->primary_key, $pk)
                 ->update($this->table, $row);

        log_debug($this->db->last_query());

        return ($this->db->affected_rows() > 0);
    }

    // ----------------------------------------------------------------------

    /**
     * Function: Update
     *
     * This function update all rows, following an specific
     * condition passed as first parameter
     *
     * @access public 
     * @param  string  
     * @param  stdclass|array
     * @return numeric
     */
    public function update_all( $condition = '1', $row = array())
    {
        if(count((array)$row) == 0)
        {
            return 0;
        }

        $this->db->update($this->table, $row, $condition);

        log_debug($this->db->last_query());
        
        return $this->db->affected_rows();
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: delete
     *
     * This function delete a row based on the 'primary key', to
     * delete multiple rows, use delete_all function
     *
     * @access public
     * @param  string
     * @return boolean
     */
    public function delete( $pk = '' )
    {
        if(empty($pk))
        {
            return FALSE;
        }

        $this->db->where($this->primary_key, $pk)
                 ->delete($this->table);

        log_debug($this->db->last_query());
        
        return ($this->db->affected_rows() > 0);
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: delete_all
     *
     * This function delete all rows under a passed condition,
     * and return a numeric value with the amount of rows affected
     *
     * @access public 
     * @param  string
     * @return numeric
     */
    public function delete_all( $condition = '1' )
    {
        if(empty($condition))
        {
            return 0;
        }

        if($condition == '1')
        {
            $current_rows = $this->count();
            $this->db->truncate();
            return $current_rows;
        }

        $this->db->where($condition);
        $this->db->delete($this->table);

        log_debug($this->db->last_query());
        
        return $this->db->affected_rows();
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: count
     *
     * This function mimic the action of delete all rows 
     * from the table
     *
     * @access public 
     * @param  string
     * @return numeric
     */
    public function count( $condition = '1')
    {
        $result = 0;
        if($condition == '1')
        {
            $result = $this->db->count_all($this->table);
        }
        else
        {
            $result = $this->db->where($condition)
                               ->count_all_results($this->table);
        }

        log_debug($this->db->last_query());
        
        return $result;
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: retrieve_by_pk()
     *
     * This function return a row, based on the selected
     * 'primary_key' field defined priviously, return FALSE
     * if the row was NOT found
     *
     * @access public
     * @param  string 
     * @return stdclass
     */
    public function retrieveByPK( $pk = '' )
    {
        return $this->retrieve_by_pk($pk);
    }

    public function retrieve_by_pk( $pk = '' )
    {

        if(empty($pk))
        {
            return FALSE;
        }

        $query = $this->db->get_where($this->table, array($this->primary_key => $pk), 1);
        
        log_debug($this->db->last_query());
        
        return ($query && $query->num_rows() > 0) ? $query->row() : FALSE;
    }

    // --------------------------------------------------------------------------------

    /**
     * Function: query
     *
     * this function is for keep the option to
     * directly run a query
     *
     * @access public
     * @param  String query
     * @return object
     *
     */
    public function query($sql = "")
    {
        if(empty($sql))
        {
            return FALSE;
        }

        return $this->db->query($sql);
    }

    // --------------------------------------------------------------------------------    
}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */
