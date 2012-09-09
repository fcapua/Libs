<?php

class ErrorHandler extends BaseSingleton{
    
    private static $_receivers = null;
    private static $_host = null;
    private static $_subject = null;
    private static $_errorType = null;
    private static $_debugMode = null;
    
    public function __construct()
    {
        self::$_receivers = array('facundocapua@gmail.com');
        self::$_debugMode = DEBUG_MODE;
        self::$_host = $_SERVER['HTTP_HOST'];
        self::$_subject = 'Error en '.self::$_host;
        self::$_errorType = array(1=>"Error", 2=>"Warning", 4=>"Parsing Error", 8=>"Notice", 16=>"Core Error", 32=>"Core Warning", 64=>"Compile Error", 128=>"Compile Warning", 256=>"User Error", 512=>"User Warning", 1024=>"User Notice", 2048=>"PHP5 Strict Warning");
        
    }
    
    public function handle($errno, $errstr, $errfile, $errline, $errctx)
    {
        $error_handler_string =  "<font size=2 face=Arial><h3>Error en ".self::$_host."<br></h3><b>Date: </b>".date('F j, Y, H:i:s a')."<br><b>Error Type: </b>". self::$_errorType[$errno]." (".$errno.")<br><b>Description: <font color=ff0000>".$errstr."</font></b><br><b>Error File: </b>".$errfile."<br><b>Error Line: </b>".$errline."<br><br>";
        while( isset($_SESSION) && list($var, $val) = each($_SESSION) ) $error_handler_string .= "_SESSION[".$var."] = ".$val."<BR>"; 
	    while( isset($_GET) && list($var, $val) = each($_GET) ) $error_handler_string .=  "_GET[".$var."] = ".$val."<BR>"; 
	    while( isset($_POST) && list($var, $val) = each($_POST) ) $error_handler_string .=  "_POST[".$var."] = ".$val."<BR>";
	    while( isset($_COOKIE) && list($var, $val) = each($_COOKIE) ) $error_handler_string .= "_COOKIE[".$var."] = ".$val."<BR>"; 

    	if( self::$_debugMode ){
    		die($error_handler_string);
    	}else{
    		foreach( self::$_receivers as $receiver ){
    			enviarEmail($receiver, self::$_subject, $error_handler_string);
    		}
    	
    		if ($errno & (E_WARNING | E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR)){
    			echo "<script>location.href = '".BASIC_MODULE_URL."/error.php'</script>";
    			exit();
    		}
    	}           
    }
}