<?php
abstract class BaseBatch
{
	/**
     * 
     * Parse given parameters and executes the run method
     * @param mixed $parameters
     */
    public static function initialize($parameters)
    {
        $class = get_called_class();
        foreach($parameters as $parameter){
            if(strpos($parameter,'--')===0){
                $property = StringHelper::camelize(substr($parameter, strpos($parameter,'--')+2));
                 if(strpos($property, '=') !== false){
                     $aux = explode('=', $property);
                     $property = $aux[0];
                     $value = $aux[1];
                 }else{
                     $value = true;
                 }
                 
                 if(property_exists($class, $property)){
                     $class::$$property = $value;
                 }                
            }
        }
        
        if(method_exists($class, 'postInitialize')){
            $class::postInitialize();
        }
            
        $class::run();
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $message
     */
    public static function log($message)
    {
       echo $message."\n"; 
    }
    
    abstract public static function run();
}