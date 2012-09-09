<?php
class RequestHelper
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    
    public static function load($formname, $else='', $clean=false)
    {
        $return = null;
    	$array = array_merge($_POST, $_GET);
    	if(is_numeric($else)){
    		$return = isset($array[$formname])? intval($array[$formname]) : intval($else) ;
    	}elseif(isset($array[$formname]) && is_array($array[$formname])){
    	    $return = array();
    	    foreach($array[$formname] as $key=>$value){
    	        $return[$key] = self::sanitize($value, $clean);
    	    }
    	}else{
            $return = isset($array[$formname]) ? self::sanitize($array[$formname], $clean) : $else ;
    	}

        return $return;
    }
    
    public static function sanitize($value, $clean=false)
    {
        if($clean){
            $return = mysql_escape_string(sacarXss(strip_tags($value)));         
        }else{
            $return = mysql_escape_string($value);
        }
        
        return $return;
    }
    
    public static function getExternalFile($file)
    {
    	$content = '';
        if(ini_get('allow_url_fopen')){
    		$content = file_get_contents($file);
    	}else{
    		$ch = curl_init ($file) ;
    		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
    		$content = curl_exec ($ch) ;
    		curl_close ($ch) ;
    	}
    	
    	return $content;
    }
    
    public static function querystringWithoutParameter($parameter)
    {
	    return preg_replace('/[\&\?]?'.$parameter.'=[^\&]*/','', (empty($_SERVER['QUERY_STRING'])?'':$_SERVER['QUERY_STRING']) );
    }

    public static function keepqsWithoutParameter($parameter, $keepqs = '')
    {
    	if( empty($keepqs) ) global $keepqs;
    	$keepqs = urldecode($keepqs);
    
    	return str_replace('&&','&',preg_replace('/[\&\?]?'.$parameter.'=[^\&]*/','', $keepqs));
    }

    public static function currentPage()
    {
	    return urlencode($_SERVER['HTTP_HOST']. $_SERVER['PHP_SELF']. ( empty($_SERVER['QUERY_STRING'])?'':'?'.$_SERVER['QUERY_STRING'] ) );
    }
    
    public static function redirect($url)
    {
        exit("<script>location.href='".$url."'</script>");
    }
    
    public static function isMethod($method){
        return $_SERVER['REQUEST_METHOD'] == $method;
    }

    public static function savePostInSession()
    {
        Session::getInstance()->set('saved_post', serialize($_POST));
    }

    public static function recoverPostFromSession()
    {
        if(Session::getInstance()->get('saved_post')){
            $_POST = unserialize(Session::getInstance()->get('saved_post'));
            Session::getInstance()->delete('saved_post');
        }
    }

    public static function getUserIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}