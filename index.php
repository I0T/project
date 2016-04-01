<?php
/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 * $Id: index.php 26007 2014-08-08 01:25:23Z jinlong $
 */

//ini_set("display_errors", "On");

//判断正式机
if(isset($_SERVER["SITE_ENV"]) && $_SERVER["SITE_ENV"] == 'production') {
	define('ENVIRONMENT', 'production');
} else {
	define('ENVIRONMENT', 'testing');
}


if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			error_reporting(E_ALL & ~ E_NOTICE);
		break;

		case 'testing':
			error_reporting(E_ALL & ~ E_NOTICE);
		break;

		case 'production':
			error_reporting(E_ALL & ~ E_NOTICE);
			//error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}


if(isset($_SERVER["SITE_ENV"]) && $_SERVER["SITE_ENV"] == 'production'){
	$system_path = dirname(dirname(__FILE__)).'/ci_2.1.3';
}else{
	$path = pathinfo(dirname(dirname(__FILE__)));
	if($path['basename']=='branches'){
		$system_path = dirname(dirname(dirname(dirname(__FILE__)))).'/ci_2.1.3';
	}else{
		$system_path = dirname(dirname(dirname(__FILE__))).'/ci_2.1.3';
	}
}


$application_folder = dirname(__FILE__).'/app';


// Set the current directory correctly for CLI requests
if (defined('STDIN'))
{
	chdir(dirname(__FILE__));
}

if (realpath($system_path) !== FALSE)
{
	$system_path = realpath($system_path).'/';
}

// ensure there's a trailing slash
$system_path = rtrim($system_path, '/').'/';

// Is the system path correct?
if ( ! is_dir($system_path))
{
	exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}

// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// The PHP file extension
// this global constant is deprecated.
define('EXT', '.php');

// Path to the system folder
define('BASEPATH', str_replace("\\", "/", $system_path));

// Path to the front controller (this file)
define('FCPATH', str_replace(SELF, '', __FILE__));

// Name of the "system folder"
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));


// The path to the "application" folder
if (is_dir($application_folder))
{
	define('APPPATH', $application_folder.'/');
}
else
{
	if ( ! is_dir(BASEPATH.$application_folder.'/'))
	{
		exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
	}

	define('APPPATH', BASEPATH.$application_folder.'/');
}

require_once BASEPATH.'core/CodeIgniter.php';

