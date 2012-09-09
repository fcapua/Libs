<?php
class FileHelper
{
    public static function getFileSize($bytes) {
       if ($bytes >= 1099511627776) {
           $return = round($bytes / 1024 / 1024 / 1024 / 1024, 2);
           $suffix = "TB";
       } elseif ($bytes >= 1073741824) {
           $return = round($bytes / 1024 / 1024 / 1024, 2);
           $suffix = "GB";
       } elseif ($bytes >= 1048576) {
           $return = round($bytes / 1024 / 1024, 2);
           $suffix = "MB";
       } elseif ($bytes >= 1024) {
           $return = round($bytes / 1024, 2);
           $suffix = "KB";
       } else {
           $return = $bytes;
           $suffix = "Bytes";
       }
       if ($return == 1) {
           $return .= " " . $suffix;
       } else {
           $return .= " " . $suffix . " ";
       }
       
       return $return;
    }

    public static function fileSystemName($nombre, $caracter = '_'){
    	$search = array(
    		chr(192),chr(193),chr(194),chr(195),chr(224),chr(225),chr(226),chr(227), // a
    		chr(201),chr(202),chr(233),chr(234), // e
    		chr(205),chr(237), // i
    		chr(211),chr(212),chr(213),chr(243),chr(244),chr(245), // o
    		chr(218),chr(220),chr(250),chr(252), // u
    		chr(199),chr(231), // c
    		chr(209),chr(241) // 
    	); 
    	$replace = array(
    		'a','a','a','a','a','a','a','a',
    		'e','e','e','e',
    		'i','i',
    		'o','o','o','o','o','o',
    		'u','u','u','u',
    		'c','c',
    		'n','n'
    	);
    	$aux = strtolower(str_replace($search, $replace, $nombre));	
    	$aux = str_replace(' ',$caracter,$aux);
    	$aux = preg_replace('/[^a-z0-9]/',$caracter,$aux);
    	
    	return $aux;
    }
    
    public static function folderExists($folder)
    {
        
        return file_exists($folder) && is_dir($folder);
    }
    
    public static function recursiveCopy($source, $dest, $excludes=array())
    {
        $content = scandir($source);
        
        if(!self::folderExists($dest)){
            mkdir($dest);
        }
        
        foreach($content as $filename){
            $filename = trim($filename);
            if(strpos($filename,'.')!==0){
                $filepath = $source.DIRECTORY_SEPARATOR.$filename;
                $destpath = $dest.DIRECTORY_SEPARATOR.$filename;
                if(is_file($filepath)){
                    copy($filepath, $destpath);
                }elseif(is_dir($filepath) && !StringHelper::matchRegexs($filepath, $excludes)){
                    self::recursiveCopy($filepath, $destpath, $excludes);
                }
            }            
        }
        
        return true;
    }
    
	/**
     * 
     * Gets the file extension for the given filename
     * @param string $filename
     * 
     * @return string $fileextension
     */
    public static function getFileExtension($filename)
    {
        
        return strtolower(substr($filename, strrpos($filename,'.')+1));
    }
    
    public static function getFilesFromFolder($folder, $pattern='*', $recursive=false, $onlyDir=false)
    {
        $files = glob($folder.$pattern, ($onlyDir ? GLOB_ONLYDIR : null));
        $return = array();
        foreach($files as $file){
            $fileArray = array('path' => $file);
            if($recursive===true && is_readable($file)){
                $fileArray['files'] = self::getFilesFromFolder($file, $pattern, $recursive, $onlyDir);
            }
            $return[] = $fileArray;
        }
        
        return $return; 
    }
}