<?php
class DatabaseHelper
{
    const INSERT_QUERY = 1;
    const UPDATE_QUERY = 2;
    /**
     * 
     * Builds a query statement base on given parameters
     * @param string $table : Name of the table
     * @param mixed $fields : Array with fields as key and wanted value as value. Ex: array('id'=>1, 'name'=>'John')
     * @param int $type : Type of the wanted statement (INSERT_QUERY, UPDATE_QUERY)
     * @param string $unique_field (optional) : Name of the unique field in the table. Default: 'id'
     * @param boolean $author : Determines if the author and timestamp has to been save. Default: false
     * 
     * @return string SQL Statement
     * 
     * @author Facundo Capua (facundocapua@gmail.com)
     */
    
    public static function buildQuery($table, $fields, $type, $unique_field='id', $author=false){
        $return = $unique_value = '';
        if(isset($fields[$unique_field])){
            $unique_value = $fields[$unique_field];
            unset($fields[$unique_field]);
        }
    
        switch ($type){
            case self::INSERT_QUERY:
                if($author){
                    $fields['created_by'] = Session::getInstance()->get('adminCodigo');
                    $fields['created_at'] = time();
                }
                $return = 'INSERT INTO `'.$table.'` 
                		   ('.implode(',',array_map(create_function('$value', 'return "`".$value."`";'),array_keys($fields))).') 
                		   VALUES ('.implode(',',array_map(create_function('$value', 'return "\'".$value."\'";'),array_values($fields))).')';
                break;
                
            case self::UPDATE_QUERY:
                if($author){
                    $fields['updated_by'] = Session::getInstance()->get('adminCodigo');
                    $fields['updated_at'] = time();
                }
                $return = 'UPDATE `'.$table.'` SET 
                			'.implode(',',array_map(create_function('$key,$value', 'return "`".$key."`=\'".$value."\'";'),array_keys($fields),array_values($fields))).'
                			WHERE `'.$unique_field.'` = \''.$unique_value.'\'';
                
                break;
        }
        
        return $return;
    }

}