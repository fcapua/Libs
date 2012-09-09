<?php
class VirtualHost{
    private $_filename = null;
    private $_filePath = null;
    private $_symlinkPath = null;
    private $_serverName = null;
    private $_documentRoot = null;
    private $_active = null;
    
    
    private static $_validAttributes = array('ServerName', 'DocumentRoot');
    
	/**
     * @return the $_filename
     */
    public function getFilename()
    {
        return $this->_filename;
    }

	/**
     * @param field_type $_filename
     */
    public function setFilename($_filename)
    {
        $this->_filename = $_filename;
        
        return $this;
    }

	/**
     * @return the $_filePath
     */
    public function getFilePath()
    {
        return $this->_filePath;
    }

	/**
     * @param field_type $_filePath
     */
    public function setFilePath($_filePath)
    {
        $this->_filePath = $_filePath;
        
        return $this;
    }

	/**
     * @return the $_symlinkPath
     */
    public function getSymlinkPath()
    {
        return $this->_symlinkPath;
    }

	/**
     * @param field_type $_symlinkPath
     */
    public function setSymlinkPath($_symlinkPath)
    {
        $this->_symlinkPath = $_symlinkPath;
        
        return $this;
    }

	/**
     * @return the $_serverName
     */
    public function getServerName()
    {
        return $this->_serverName;
    }

	/**
     * @param field_type $_serverName
     */
    public function setServerName($_serverName)
    {
        $this->_serverName = $_serverName;
        
        return $this;
    }

	/**
     * @return the $_documentRoot
     */
    public function getDocumentRoot()
    {
        return $this->_documentRoot;
    }

	/**
     * @param field_type $_documentRoot
     */
    public function setDocumentRoot($_documentRoot)
    {
        $this->_documentRoot = $_documentRoot;
        
        return $this;
    }
    

	/**
     * @return the $_active
     */
    public function isActive()
    {
        return $this->_active;
    }

	/**
     * @param field_type $_active
     */
    public function setActive($_active)
    {
        $this->_active = $_active;
        
        return $this;
    }

	/**
     * 
     * Enter description here ...
     * @param unknown_type $string
     */
    public function setAttribute($string)
    {
        $string = str_replace('"', '', $string);
        $array = explode(' ',$string);
        if(sizeof($array) == 2){
            $attribute = $array[0];
            $value = $array[1];
            $method = 'set'.$attribute;
            if(in_array($attribute, self::$_validAttributes) && method_exists($this, $method)){
                $this->$method($value);
            }
        }
        
        return $this;
    }
    
    /**
     * 
     */
    public function deactivate()
    {
        $vhost_symlink = $this->getSymlinkPath().$this->getFilename();
        if($this->isActive() && file_exists($vhost_symlink) && is_link($vhost_symlink)){
            unlink($vhost_symlink);
            ApacheUtils::restartServer();
            
            return true;
        }
        
        return false;
    }
    
    /**
     * 
     * 
     */
    public function activate()
    {
        $vhost_symlink = $this->getSymlinkPath().$this->getFilename();
        if(!$this->isActive() && !file_exists($vhost_symlink)){
            symlink($this->getFilePath().$this->getFilename(), $vhost_symlink);
            ApacheUtils::restartServer();
            
            return true;
        }
        
        return false;
    }
}