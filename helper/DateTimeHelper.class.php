<?php
/**
 * 
 * Date/Time helper functions
 * @author Facundo Capua <facundocapua@gmail.com>
 *
 */
class DateTimeHelper
{
    public static function convertToFormat($src_date, $src_format, $dst_format)
    {
        if(function_exists('date_create_from_format')){
            $date = date_create_from_format($src_format, $src_date);
        
            return date_format($date, $dst_format);            
        }else{
            
            return date($dst_format, strtotime($src_date)); //Not much reliavable but works for now...
        }
        
    }
}