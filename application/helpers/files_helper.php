<?php
/*
 * This program is a work of the United States Government.
 * Per 17 U.S.C. § 105 this work is not copyrighted within the United States of America.
 * Outside the U.S.A this work is copyright © 2006-2014, The Library of Congress.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the the Library of Congress, the United States
 *       Government nor the names of its contributors may be used to endorse
 *       or promote products derived from this software without specific prior
 *       written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE LIBRARY AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE UNITED STATES GOVERNMENT AND CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 * GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE
 * USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
?>
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ---------------------------------------------------------------------

/**
 * Function: get_download_filename
 *
 * This function return the name of the file for 
 * download
 *
 * @access public
 * @param  string item_type
 * @param  object item
 * @return string
 */
if( ! function_exists('get_download_filename'))
{
    function get_download_filename($item_type = 'book', $item = null)
    {
        if(is_null($item))
        {
            return 'noname.zip';
        }

        switch ($item_type) 
        {
            case 'book':
                $filename = $item->bmprefix."-".substr($item->author." ".$item->title, 0, 160)." ".$item->bmcid;
                break;
            case 'magazine':
                $filename = $item->prefix."-".$item->title;
                break;
            default:
                $filename = "noname.zip";
                break;
        }

        $filename = str_replace(' ', '_', $filename);
        $filename = CleanFilename($filename);
        $filename = str_replace(';', '-', $filename);

        return "{$filename}.zip";
    }
}

// ---------------------------------------------------------------------

// ---------------------------------------------------------------------

/**
 *  Function: file_create
 *
 *  This function create a file, save the content of
 *  the file, and change the permissions to the file
 *
 * @access public 
 * @param  string 
 * @param  string
 * @param  integer
 * @return string
 */
if( ! function_exists('f_create'))
{
    function f_create( $filename, $content = '', $attr = 0775)
    {
        $handler = @fopen($filename, "a+");

        if(is_resource($handler))
        {
            ftruncate($handler, 0);
            rewind($handler);

            fwrite($handler, $content);         
            fclose($handler);
            
            @chmod($filename, $attr);
        }
        
        return (file_exists($filename) && is_readable($filename)) ? TRUE : FALSE;
    }
}

// ---------------------------------------------------------------------

/**
 * Function: f_write
 *
 * this function open a file on write mode, and write
 * a new line at the end of the file
 *
 * @access public
 * @param  string filename
 * @param  string request
 * @return boolean
***********/
if( ! function_exists('f_write'))
{
	function f_write($filename, $content = '', $attr = 0775)
	{
		if(is_readable($filename))
		{
			if(($handler = @fopen($filename, "x+")) != FALSE)
			{
				return fwrite($handler, $content);
			}
		}
		else
		{
			return f_create($filename, $content);
		}
			
		return FALSE;
	}
}

// ---------------------------------------------------------------------

/**
 * Function: f_delete()
 *
 * This function delete a file if the file exists
 *
 * @access public 
 * @param  string 
 * @return string
 */
if( ! function_exists('f_delete'))
{
    function f_delete($filename)
    {
        if(file_exists($filename))
        {
            @unlink($filename);
        }
    }
}

// ---------------------------------------------------------------------

/**
 * Function: f_read
 *
 * This function read the content of a file, 
 * return FALSE on  any other case
 *
 * @access public 
 * @param  string
 * @return string
 */
if( ! function_exists('f_read'))
{
    function f_read( $filename )
    {
        $content = (file_exists($filename) && is_readable($filename)) ? file_get_contents($filename) : FALSE;

        return $content;
    }
}

// ---------------------------------------------------------------------

/**
 * Function: CleanFilename()  
 *
 * Remove the following list of characters from 
 * filename. (any string passed)
 *
 * @access public
 * @param  string filename
 * @return string
 */
if( ! function_exists('CleanFilename'))
{
    function CleanFilename( $filename )
    {
        // ---------------------------------------
        // replace all characters on the array 
        // by the underscore character
        // ---------------------------------------
        $forbidden_characters = str_split('<>:"/\|?*,');
        foreach($forbidden_characters as $char)
        {
            $filename = mb_replace($char, '_', $filename);
        }

        // ---------------------------------------
        // replace consecutive underscores with 
        // only one underscore
        // ---------------------------------------
        $filename = mb_ereg_replace('[_]{2,}', '_', $filename);

        log_message('error', 'Downloading this file: ' . $filename);

        return $filename;
    }
}

// ---------------------------------------------------------------------

/**
 * Function: get_file_permission
 * 
 * The function return a file permission.
 *
 * @access public
 * @param  string full_path
 * @return string
 */
if( ! function_exists('get_file_permission'))
{
    function get_file_permission( $full_path = '')
    {
        if(empty($full_path))
        {
            return '---------';
        }
        
        $perms = fileperms($full_path);
        
        if (($perms & 0xC000) == 0xC000) // Socket
        {
            $info = 's';
        } 
        elseif (($perms & 0xA000) == 0xA000) // Symbolic Link
        {
            $info = 'l';
        } 
        elseif (($perms & 0x8000) == 0x8000) // Regular
        {
            $info = '-';
        } 
        elseif (($perms & 0x6000) == 0x6000) // Block special
        {   
            $info = 'b';
        } 
        elseif (($perms & 0x4000) == 0x4000) // Directory
        {   
            $info = 'd';
        } 
        elseif (($perms & 0x2000) == 0x2000) // Character special
        {   
            $info = 'c';
        } 
        elseif (($perms & 0x1000) == 0x1000) // FIFO pipe
        {   
            $info = 'p';
        } 
        else // Unknown
        {
            $info = 'u';
        }
        
        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
                 (($perms & 0x0800) ? 's' : 'x' ) :
                 (($perms & 0x0800) ? 'S' : '-'));
        
        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
                 (($perms & 0x0400) ? 's' : 'x' ) :
                 (($perms & 0x0400) ? 'S' : '-'));
        
        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
                 (($perms & 0x0200) ? 't' : 'x' ) :
                 (($perms & 0x0200) ? 'T' : '-'));
        
        return $info;
    }
}

// ---------------------------------------------------------------------

/** 
 * Function get_handler()
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
    function get_handler($filename)
    {
        $handler   = null;
        if(file_exists($filename))
        {
            $handler = fopen($filename, "a+");
        }
        else
        {
            $handler = fopen($filename, "a+");
            fwrite($handler, "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?"."> \n");
            fwrite($handler, "\n");
        }

        return $handler;
    }
}

// ---------------------------------------------------------------------
// ---------------------------------------------------------------------

/* End of file file_helper.php */
/* Location: ./application/helpers/file_helper.php */

