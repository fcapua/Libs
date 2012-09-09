<?php
class CSVParser{
    
    const FIELD_SEPARATOR = ";";
    
    public static function ArrayToCSV($array, $keys_first_row=false){
        $return = "";
        if($keys_first_row===true){
            $return = "";
            $first_row = reset($array);
            $keys = array_keys($first_row);
            $return .= self::LineToCSV($keys);
        }
        
        foreach($array as $line){
            $return .= self::LineToCSV($line);
        }
        
        return $return;
    }
    
    public static function LineToCSV($array){
        $return = "";
        foreach($array as $value){
            $return .= "\"{$value}\"".self::FIELD_SEPARATOR;
        }
        $return = substr($return,0,-1);
        $return .= "\r\n";
        
        return $return;
    }
}