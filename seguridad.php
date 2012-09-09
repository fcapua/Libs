<?php
if($_SERVER['SCRIPT_NAME'] != BASIC_MODULE_URL.'/login.php'){
    if( Session::getInstance()->get('adminPermisos') === null || Session::getInstance()->get('adminCodigoMD5') === null ){
    	exit("<script>location.href = '".BASIC_MODULE_URL."/login.php?1'</script>");
    }

    #Regenero todas las sesiones, para que no se corrompan las mismas:
    $query = "select id, first_name, last_name, overall_right, credentials from administrator where status = 1 and MD5(concat('".SITE_HASH."',id)) = '".Session::getInstance()->get('adminCodigoMD5')."'";
    $rs = Database::getInstance()->query($query);
    if($rs){
    	Session::getInstance()->set('adminCodigo', $rs[0]['id']);
    	Session::getInstance()->set('adminCodigoMD5', md5(SITE_HASH.$rs[0]['id']));
    	Session::getInstance()->set('adminNombre', $rs[0]['first_name'].' '.$rs[0]['last_name']);
    	Session::getInstance()->set('adminPermisoSobreTodo', $rs[0]['overall_right']);
        Session::getInstance()->set('adminPermisos', explode(',', $rs[0]['credentials']));
    	
        $query = "Update administrator set last_login='".time()."' where status = 1 and MD5(concat('".SITE_HASH."',id)) = '".Session::getInstance()->get('adminCodigoMD5')."'";
        Database::getInstance()->query($query) ;
    }else{
    	exit("<script>location.href = '".BASIC_MODULE_URL."/login.php?logout=1'</script>");
    }
}

function has_credential($credential){
	return in_array($credential, Session::getInstance()->get('adminPermisos'));
}


if(defined('REQUIRED_CREDENTIAL') && !defined('AVOID_CREDENTIAL_CHECK') && !has_credential(REQUIRED_CREDENTIAL)){
    exit("<script>location.href = '".BASIC_MODULE_URL."/access_deny.php'</script>");
}

