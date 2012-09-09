<?php
class ApacheUtils
{
    const ROOT = '/etc/apache2/';
    const VHOSTS_DIR_AVAILABLE = 'sites-available/';
    const VHOSTS_DIR_ENABLED = 'sites-enabled/';
    
    private static $_sitesAvailable = null;
    private static $_sitesEnabled = null;
    
    public static function getAvailableVirtualHosts()
    {
        if(self::$_sitesAvailable === null)
        {
            self::$_sitesAvailable = array();
            $vhost_path = self::ROOT.self::VHOSTS_DIR_AVAILABLE;
            $vhost_enabled_path = self::ROOT.self::VHOSTS_DIR_ENABLED;
            $files = self::getVirtualHostFilesFromDir($vhost_path);
            foreach($files as $filename){
                $vhost = self::parseVirtualHostFile($vhost_path.$filename);
                $vhost->setFilename($filename)
                        ->setFilePath($vhost_path)
                        ->setSymlinkPath($vhost_enabled_path)
                        ->setActive((file_exists($vhost_enabled_path.$filename) && is_link($vhost_enabled_path.$filename)));
                self::$_sitesAvailable[] = $vhost;
            }
        }
        
        return self::$_sitesAvailable;
    }
    
    public static function getVirtualHostByName($name)
    {
        $vhosts = self::getAvailableVirtualHosts();
        foreach($vhosts as $vhost){
            if($vhost->getServerName() == $name){
                return $vhost;
            }
        }
        
        return null;
    }
    
    public static function parseVirtualHostFile($filename)
    {
        $content = file_get_contents($filename);
        
        $regex = '/<VirtualHost[^>]*>(.*?)<\/VirtualHost>/s';
        $result = preg_match_all($regex, $content, $matches);
        if($result){
            $vhost_string = $matches[1][0];
            $lines = preg_split("/(\r?\n)/", $vhost_string);
            $virtualHost = new VirtualHost();
            foreach($lines as $line){
                $line = trim($line);
                $virtualHost->setAttribute($line);
            }
            
            return $virtualHost;
        }
        
        return false;
    }
    
    private static function getVirtualHostFilesFromDir($dir_name)
    {
        $content = scandir($dir_name);
        $return = array();
        foreach($content as $filename){
            $filename = trim($filename);
            if(!is_dir($filename) && !StringHelper::endsWith($filename, '.bak')){
                $return[] = $filename;
            }
        }
        
        return $return;
    }
    
    public static function restartServer()
    {
        file_put_contents('/var/tmp/admin-sites/restart-apache', 'Restart apache');
    }
}