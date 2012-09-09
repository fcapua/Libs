<?php

/**
 * Description of ImageCropper
 *
 * @author facundocapua
 */
class ImageCropper
{
    private $_name = null;
    private $_image = null;
    private $_cropAspect = null;
    private $_cropWidth = null;
    private $_cropHeight = null;
    private $_containerMaxHeight = null;
    private $_containerMaxWidth = null;
    private $_callback = null;
    private $_handler = null;
    
    private static $_instances = array();
    
    public function __construct($name)
    {
        if(!in_array($name, self::$_instances)){
            self::$_instances[$name] = $name;
            $this->_name = $name;
        }else{
            throw new Exception('An '.__CLASS__.' instance was already created for name: '.$name);
        }
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function setImage($image)
    {
        $this->_image = $image;
        
        return $this;
    }
    
    public function getImage()
    {
        return $this->_image;
    }
    
    public function setCropAspect($aspect)
    {
        $this->_cropAspect = $aspect;
        
        return $this;
    }
    
    public function getCropAspect()
    {
        return $this->_cropAspect;
    }
    
    public function hasCropAspect()
    {
        return !empty($this->_cropAspect);
    }
    
    public function setCropWidth($width)
    {
        $this->_cropWidth = $width;
        
        return $this;
    }
    
    public function getCropWidth()
    {
        return $this->_cropWidth;
    }
    
    public function setCropHeight($height)
    {
        $this->_cropHeight = $height;
        
        return $this;
    }
    
    public function getCropHeight()
    {
        return $this->_cropHeight;
    }
    
    public function setContainerMaxHeight($max_height)
    {
        $this->_containerMaxHeight = $max_height;
        
        return $this;
    }
    
    public function getContainerMaxHeight()
    {
        return $this->_containerMaxHeight;
    }
    
    public function setContainerMaxWidth($max_width)
    {
        $this->_containerMaxWidth = $max_width;
        
        return $this;
    }
    
    public function getContainerMaxWidth()
    {
        return $this->_containerMaxWidth;
    }
    
    public function setCallback($callback)
    {
        $this->_callback = $callback;
        
        return $this;
    }
    
    public function getCallback()
    {
        return $this->_callback;
    }
    
    public function hasCallback()
    {
        return !empty($this->_callback);
    }
    
    public function setHandler($handler)
    {
        $this->_handler = $handler;
        
        return $this;
    }
    
    public function getHandler()
    {
        return $this->_handler;
    }
    
    public function hasHandler()
    {
        return !empty($this->_handler);
    }
    
    public function render()
    {
        include SHARED_MODULE_FOLDER . '_cropper.php';
    }
}