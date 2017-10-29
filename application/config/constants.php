<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| GENERAL Values
|--------------------------------------------------------------------------
|
| Application general values.
|
*/
defined('STR_DATETIME_FORMAT')		OR define('STR_DATETIME_FORMAT',	'Y/m/d H:i:s');
defined('STR_DATEONLY_FORMAT') 		OR define('STR_DATEONLY_FORMAT',	'Y-m-d');
defined('STR_TIMEONLY_FORMAT') 		OR define('STR_TIMEONLY_FORMAT',	'H:i:s');
defined('STR_DATETIME_EXPIRATION') 	OR define('STR_DATETIME_EXPIRATION',	'Y-m-d H:i:s');
defined('STR_DATETIME_UTC') 		OR define('STR_DATETIME_UTC',		'Y-d-mTG:i:sz');
defined('URL_SEGMENTS') 		OR define('URL_SEGMENTS',		1);

/*
|--------------------------------------------------------------------------
| URL parameters - GET Parameters
|--------------------------------------------------------------------------
|
| URL parameters definitions for ALL posible parameters on our 
| application 
|
*/
defined('URL_IP') 		OR define('URL_IP',		'ip');
defined('URL_ZIP')		OR define('URL_ZIP', 		'zip');
defined('URL_OUTPUT')		OR define('URL_OUTPUT', 	'output');
defined('URL_COUNTRY')		OR define('URL_COUNTRY',	'country');
defined('URL_REQUEST')		OR define('URL_REQUEST',	'request');

/*
|--------------------------------------------------------------------------
| Definition of Result FORMAT
|--------------------------------------------------------------------------
|
| Defintion of the result format of this web services, all possible 
| values are XML, JSON
|
*/
defined('RST_XML') 		OR define('RST_XML',		'xml');
defined('RST_JSON') 		OR define('RST_JSON',		'json'); 

/*
|--------------------------------------------------------------------------
| Structure of the Exception
|--------------------------------------------------------------------------
|
| Defintion of the exception attributes
|
*/
defined('EX_CODE')		OR define('EX_CODE',		'code');
defined('EX_MSG')		OR define('EX_MSG',		'message');

/*
|--------------------------------------------------------------------------
| Default values for parameters
|--------------------------------------------------------------------------
|
| Definition of the default values for parameters 
|
|
*/
defined('DFLT_OUTPUT') 		OR define('DFLT_OUTPUT',	'xml');

/*
|--------------------------------------------------------------------------
| Definition of commands
|--------------------------------------------------------------------------
|
| List definition of all commands execute by the web service 
|
|
*/
defined('CMD_ZIP')		OR define('CMD_ZIP',		'zip');
defined('CMD_IP')		OR define('CMD_IP',		'ip');
defined('CMD_AGENT')		OR define('CMD_AGENT',		'agent');
defined('CMD_REQUEST')		OR define('CMD_REQUEST',	'request');
defined('CMD_IMPORT')		OR define('CMD_IMPORT',		'import');

/*
|--------------------------------------------------------------------------
| Definition of the headers
|--------------------------------------------------------------------------
|
| headers defintion for all possible results: 
| - XML
| - JSON
*/
define('HEADERS_XML_1',             'Content-Language: en-US');
define('HEADERS_XML_2',             'Content-Type: text/xml; charset=utf-8');

define('HEADERS_JSON_1',            'Content-Language: en-US');
define('HEADERS_JSON_2',            'Content-Type: text/json; charset=utf-8');

define('HEADERS_DWN_1',             'Content-Type: application/x-download; charset=utf-8');
define('HEADERS_DWN_2',             'Content-Disposition: attachment; filename="%s"');
define('HEADERS_DWN_3',             'Content-Length: %s');
define('HEADERS_DWN_4',             'Content-Language: en-US');

/*
|--------------------------------------------------------------------------
| Definition of the Errors
|--------------------------------------------------------------------------
|
| Errors defintions
|
*/
defined('CODE_0')		OR define('CODE_0', 0);
defined('CODE_1')		OR define('CODE_1', 1);
defined('CODE_2')		OR define('CODE_2', 2);
defined('CODE_3')		OR define('CODE_3', 3);
defined('CODE_4')		OR define('CODE_4', 4);
defined('CODE_5')		OR define('CODE_5', 5);
defined('CODE_6')		OR define('CODE_6', 6);
defined('CODE_7')		OR define('CODE_7', 7);
defined('CODE_8')		OR define('CODE_8', 8);
defined('CODE_9')		OR define('CODE_9', 9);
defined('CODE_10')		OR define('CODE_10', 10);
defined('CODE_11')		OR define('CODE_11', 11);
defined('CODE_12')		OR define('CODE_12', 12);

defined('MSG_0')		OR define('MSG_0', 'Command execution was successful');
defined('MSG_1')		OR define('MSG_1', 'Command execution was successful, no result data returned');
defined('MSG_2')		OR define('MSG_2', 'Command cannot be executed - error in the submitted URL');
defined('MSG_3')		OR define('MSG_3', 'Command cannot be executed - error involves user account');
defined('MSG_4')		OR define('MSG_4', 'System unavailable');
defined('MSG_5')		OR define('MSG_5', 'Reserved');
defined('MSG_6')		OR define('MSG_6', 'Unknown or internal error');
defined('MSG_7')		OR define('MSG_7', 'Parameters values outside of valid range');
defined('MSG_8')		OR define('MSG_8', 'Unknown command');
defined('MSG_9')		OR define('MSG_9', 'Requested item does not exists');
defined('MSG_10')		OR define('MSG_10', 'Invalid item format');
defined('MSG_11')		OR define('MSG_11', 'Reserved');
defined('MSG_12')		OR define('MSG_12', 'Reserved');

/*
|--------------------------------------------------------------------------
| Debugging Constants
|--------------------------------------------------------------------------
|
| those constants are for debugging purpouse
|
|  ex: '192.168.0.35', --> Correct
|      '192.168'       --> Correct
|      '192.168.*'     --> Wrong
|
*/
defined('DBG_IP_RANGE') 	OR define('DBG_IP_RANGE',          serialize(
    array(
        '127.0.0.1',            // localhost 
	'98.169.160.183',	// From Home
	'140.147.106.60',	// From Office
    )
));

defined('DBG_STATUS') 		OR define('DBG_STATUS',            TRUE);
defined('DBG_PATH') 		OR define('DBG_PATH',              'application'.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'debug');
defined('DBG_PATH_ERRORS') 	OR define('DBG_PATH_ERRORS',       'application'.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'errors');

/*
|--------------------------------------------------------------------------
| Definition of Database Connection
|--------------------------------------------------------------------------
|
| Here is the definition of the database connection, definition include
| database name, server path, username, password and debug status
| 
|
*/
defined('DB_HOSTNAME') 		OR define('DB_HOSTNAME',	'localhost');
defined('DB_DATABASE')		OR define('DB_DATABASE',	'geo');
defined('DB_USERNAME') 		OR define('DB_USERNAME',	'root');
defined('DB_PASSWORD') 		OR define('DB_PASSWORD',	'ruso34');
defined('DB_DEBUG') 		OR define('DB_DEBUG',		FALSE);

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
