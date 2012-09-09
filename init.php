<?php
define('DS', DIRECTORY_SEPARATOR);
#CUSTOM CONFIG LOADER
if(isset($_SERVER['SCRIPT_FILENAME'])){
    $actualDirectory = dirname($_SERVER['SCRIPT_FILENAME']);
    $customConfig = $actualDirectory.DIRECTORY_SEPARATOR.'config.php';
    if(file_exists($customConfig)){
        require $customConfig;
    }
}

# TURN OF MAGIC QUOTES
if ( get_magic_quotes_gpc () ){
	function traverse ( &$arr ){
		if ( !is_array ( $arr ) ) return;
		foreach ( $arr as $key => $val )
			is_array ( $arr[$key] ) ? traverse ( $arr[$key] ) : ( $arr[$key] = stripslashes ( $arr[$key] ));
	}
	$gpc = array ( &$_GET, &$_POST, &$_COOKIE, &$_REQUEST );
	traverse ( $gpc );
}

#AUTOLOAD CLASSES
require LIB_FOLDER.'Autoloader.class.php';
$autoloader = Autoloader::getInstance();
spl_autoload_register(array($autoloader, 'load'));



#ERROR HANDLER
ini_set('display_errors',1);
set_error_handler(array(ErrorHandler::getInstance(), "handle"));
error_reporting(E_ALL);

#SESSION INIT
Session::getInstance();