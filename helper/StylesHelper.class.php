<?php
class StylesHelper
{
    
    private static $_styles = null;
    
    public static function printStyles()
    {
        echo self::getStyles();
    }

    public static function getStyles($list=null)
    {
        if(self::$_styles === null){
            self::$_styles = '';
            $cssFiles = glob(CSS_FOLDER.'*.css');
            foreach($cssFiles as $cssFile){
                if($list==null || in_array($cssFile, $list)){
                    self::$_styles .= file_get_contents($cssFile);
                }
            }
            
            self::$_styles = str_replace('{IMAGE_URL}',IMAGES_URL, self::$_styles);
        }   

        return self::$_styles;
    }
}