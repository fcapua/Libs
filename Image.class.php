<?php
class Image
{
    private $format = null;
    private $src = null;
    private $width = null;
    private $height = null;
    
    private $image = null;
    
    public function __construct($filepath)
    {
        $extension = FileHelper::getFileExtension($filepath);
        switch(strtolower($extension)){
            case 'jpg':
            case 'jpeg':
                $this->image = imagecreatefromjpeg($filepath);
                $this->format = 'jpg';
                break;
            case 'gif':
                $this->image = imagecreatefromgif($filepath);
                $this->format = 'gif';
                break;
            case 'png':
                $this->image = imagecreatefrompng($filepath);
                $this->format = 'png';
                break;
            default:
                throw new Exception(__CLASS__.' - '.__METHOD__.': Image type not supported');
                break;
        }
        
        list($width, $height) = getimagesize($filepath);
        $this->width = $width;
        $this->height = $height;
    }
    
    
    public function crop($newWidth, $newHeight, $posX, $posY)
    {
        $newImg = ImageCreateTrueColor($newWidth, $newHeight);
	    imagecopyresampled($newImg,$this->image,0,0,$posX,$posY, $newWidth,$newHeight,$this->width,$this->height);
	    $this->image = $newImg;
    }

    public function resize($maxWidth, $maxHeight)
    {
        if($this->width > $this->height){
            $newWidth = $maxWidth;
            $newHeight = ($newWidth * $this->height) / $this->width;
        }else{
            $newHeight = $maxHeight;
            $newWidth = ($newHeight * $this->width) / $this->height;
        }

        $this->crop($newWidth, $newHeight, 0, 0);
    }
    
    public function save($filepath)
    {
        $extension = FileHelper::getFileExtension($filepath);
        switch(strtolower($extension)){
            case 'jpg':
            case 'jpeg':
                $this->image = imagejpeg($this->image, $filepath, 100);
                chmod($filepath, 0777);
                break;
            case 'gif':
                $this->image = imagegif($this->image, $filepath);
                $this->format = 'gif';
                chmod($filepath, 0777);
                break;
            case 'png':
                $this->image = imagepng($this->image, $filepath);
                $this->format = 'png';
                chmod($filepath, 0777);
                break;
            default:
                throw new Exception(__CLASS__.' - '.__METHOD__.': Image type not supported');
                break;
        }
    }
}