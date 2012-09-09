<?php
/**
 * 
 * @author Facundo Capua <facundocapua@gmail.com>
 *
 */
class FileExplorer
{
    private $_fieldName = null;
    private $_folder = null;
    private $_fileExtensions = null;
    private $_recursive = true;
    private $_multiple = false;
    private $_onlyDirs = false;
    
    private $_template = null;
    
    private $_files = null;
    
    public function __construct($folder, $fieldName='fileExplorer')
    {
        $this->_folder = $folder;
        $this->_fieldName = $fieldName;
        
        $this->_template = SHARED_MODULE_FOLDER.'_fileExplorer.php';
        $this->_fileExtensions = '*';
    }
    
	/**
     * @return the $_fileExtensions
     */
    public function getFileExtensions()
    {
        return $this->_fileExtensions;
    }

	/**
     * @param field_type $_fileExtensions
     */
    public function setFileExtensions($_fileExtensions)
    {
        $this->_fileExtensions = $_fileExtensions;
    }

	/**
     * @return the $_recursive
     */
    public function getRecursive()
    {
        return $this->_recursive;
    }

	/**
     * @param field_type $_recursive
     */
    public function setRecursive($_recursive)
    {
        $this->_recursive = $_recursive;
    }

	/**
     * @return the $_multiple
     */
    public function getMultiple()
    {
        return $this->_multiple;
    }

	/**
     * @param field_type $_multiple
     */
    public function setMultiple($_multiple)
    {
        $this->_multiple = $_multiple;
    }
	/**
     * @return the $_template
     */
    public function getTemplate()
    {
        return $this->_template;
    }

	/**
     * @param field_type $_template
     */
    public function setTemplate($_template)
    {
        $this->_template = $_template;
    }

    /**
     * @return the $_onlyDirs
     */
    public function getOnlyDirs()
    {
        return $this->_onlyDirs;
    }

	/**
     * @param field_type $_onlyDirs
     */
    public function setOnlyDirs($_onlyDirs)
    {
        $this->_onlyDirs = $_onlyDirs;
    }

	public function getFiles($force=false)
    {
        if($this->_files === null || $force===true){
            $this->_files = FileHelper::getFilesFromFolder($this->_folder, $this->_fileExtensions, $this->_recursive, $this->_onlyDirs);
        }
        
        return $this->_files;
    }
}