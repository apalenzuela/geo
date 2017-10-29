<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

/**
 * VAR_DUMP Library
 * 
 * This library are intended to grouping all 
 * function to handle the user authentication 
 * on the system
 * 
 * @author Jose Alain Palenzuela <alpa@loc.gov>
 *
 * @since 1.0
 */
class VD
{
    /**
     * Function: __construct
     *
     * This function is the constructor of 
     * the class
     *
     * @access public
     * @param  array mixed
     * @return void
     */
    public function __construct()
    {
        // self::show(func_get_args());
    }

    // ---------------------------------------------------------

    /**
     * Function: show
     *
     * This function make a loop with all arguments
     * to var_dump each 
     *
     * @access public
     * @param  array of args
     * @return void
     */
    public static function show()
    {
        $args = func_get_args();
        foreach($args as $arg)
        {
            echo "<pre>";
            var_dump($arg);
            echo "</pre>";
        }
    }

    // ---------------------------------------------------------

    /**
     * Function: stop;
     *
     * This function call the function vd to show
     * the var_dump of the args passed and later stop 
     * the execution calling stop function
     *
     * @access public
     * @param  array of args
     * @return void
     */
    public static function stop()
    {
        $args = func_get_args();
        foreach ($args as $arg) 
        {
            self::show($arg);
        }
        self::stopped();
    }

    // ---------------------------------------------------------

    /*
    public function __callStatic($function, $values)
    {
        switch ($function) 
        {
            case 'st':
                var_dump($function);
                break;
            
            default:
                var_dump($function);
                break;
        }
    }
    */

    // ---------------------------------------------------------

    /**
     * Function: type
     *
     * This function will return the type of the variable
     * passed as parameter. 
     *
     * @access public
     * @param  mix
     * @return string
     */
    public static function type()
    {
        foreach(func_get_args() as $arg)
        {
            echo "<pre>";
            echo gettype($arg);
            echo "</pre>";
        }
    }

    // ---------------------------------------------------------

    /**
     * Function: filename
     *
     * This function save the echo of var_dump to
     * a file, the first argument is the name of the
     * filename where the echo will be saved
     *
     * @access public
     * @param  array of args
     * @return void
     */
    public static function filename()
    {
        $num_args = func_num_args();

        if($num_args >= 2)
        {
            $filename = func_get_arg(0);
            $output   = "";

            ob_start();

            for($i = 1; $i < $num_args; $i++)
            {
                var_dump(func_get_arg($i));
                $output .= ob_get_clean();

                var_dump($output); die;
            }

            file_put_contents($filename, $output);
        }
    }

    // ---------------------------------------------------------

    /**
     * Function: stop
     *
     * This function stop the execution of the program
     * and show file and line where was called.
     *
     * @access private
     * @param  none
     * @return void
     */
    static private function stopped()
    {
        $debug = debug_backtrace(false);
        $entry = 1;                                                   
        $type  = '';                                                                               
        
        $file_path = isset($debug[$entry]['file']) ? $debug[$entry]['file'] : '';                     
        if(!empty($file_path))                                                               
        {
            if(!(strpos($file_path, 'controllers') === FALSE)) {  $type = '__CTRL__'; }
            if(!(strpos($file_path, 'core')        === FALSE)) {  $type = '__CORE__'; }
            if(!(strpos($file_path, 'helper')      === FALSE)) {  $type = '__HELP__'; }
            if(!(strpos($file_path, 'libraries')   === FALSE)) {  $type = '__LBRY__'; }
            if(!(strpos($file_path, 'models')      === FALSE)) {  $type = '__MDEL__'; }
            if(!(strpos($file_path, 'views')       === FALSE)) {  $type = '__VIEW__'; }
        }

        $class = isset($debug[$entry + 1]['class']) ? $debug[$entry + 1]['class'] : '';
        $func  = isset($debug[$entry + 1]['function']) ? $debug[$entry + 1]['function'] : '';
        $line  = isset($debug[$entry]['line']) ? $debug[$entry]['line'] : ''; 

        $message = "[{$type}] {$class}::{$func} ($line)";
        if(empty($class))
        {
            $pathinfo = pathinfo($file_path);
            $message  = "[{$type}] {$pathinfo['basename']} ($line)";            
        }

        die($message);
    }

    // ---------------------------------------------------------
}

/* End of file vd.php */
/* Location .application/libraries/vd.php */
