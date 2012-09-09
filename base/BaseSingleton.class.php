<?php
abstract class BaseSingleton
{
    private static $_instances = null;
    
    /**
     * 
     * Get instance of BaseSingleton
     * 
     * @return BaseSingleton
     */
    public static function getInstance()
    {
        $class = get_called_class();
        if(!isset(self::$_instances[$class])){
            self::$_instances[$class] = new $class;
        }
        
        return self::$_instances[$class];
    }
}