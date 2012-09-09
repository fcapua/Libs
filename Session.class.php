<?php
/**
 *
 * Class to manipulate user sessions
 * @author Facundo Capua (facundocapua@gmail.com)
 * 
 * @method Session	getInstance
 *
 */
class Session extends BaseSingleton
{
    private static $_sessionName = SITE_NAME;
    
    public function __construct()
    {
        session_name(self::$_sessionName);
        session_cache_expire(15);
        session_start();
    }
    
    public function get($name)
    {
        $name = self::$_sessionName.$name;
        return !empty($_SESSION[$name]) ? $_SESSION[$name] : null;
    }
    
    public function set($name, $value)
    {
        $name = self::$_sessionName.$name;
        $_SESSION[$name] = $value;
    }
    
    public function delete($name)
    {
        $name = self::$_sessionName.$name;
        unset($_SESSION[$name]);
    }
    
    public function destroy()
    {
        session_destroy();
    }
}