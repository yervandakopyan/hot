<?php
	global $root;
	global $path;
	global $include_css;

	$root = getenv("DOCUMENT_ROOT");
	$path = $_SERVER['PHP_SELF'];	

	//include all files in config directory
	$files = glob('/'.$root . "/hot/config/*.php");
	foreach ($files as $file) {
    	include $file;
	}
	
	//check config/index.php values for displaying errors or not
	if ($config['debug'] == false) {
		error_reporting(0);
	} else {
		error_reporting(-1);
	}

	//always include cabstract file
	set_include_path('/'.$root.'/modules'. PATH_SEPARATOR . get_include_path());
	require_once('db/db.class.php');

	
	//class files located in same directory as autoloader
	function __autoload_HTTP_Client($class_name) {
		$path = $_SERVER['PHP_SELF'];
		$parts = explode("_",$class_name);
		$dir_parts = array();
		array_push($dir_parts,array_shift($parts));		

		$class_file = implode("_",$parts);
        $module_dir = implode("_",$dir_parts);
		
		$HC = $class_file . '.class.php';
		$test = $module_dir . '/' . $HC;

		return require_once($test);
	}
	
	spl_autoload_register('__autoload_HTTP_Client');
	
	//print_r function for better output
	function pr($a,$b=false) {
	    echo '<pre>'; 
	    print_r($a); 
	    echo "</pre>\n";
	    if($b) die();
    }
	
	define('INTERNAL_ERROR', "A internal error has occured. Please contact support or try again");
	
	
	
	function loged_in () {
		if(!($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
			header("Location: ../login.php?callback={$_SERVER[REQUEST_URI]}");
		}
	}
	
	
	
?>