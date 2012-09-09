<?php
/**
 * @author: Facundo Capua
 *        Date: 5/17/12
 */
class Autoloader
{
    const SUFFIX    = '.class';
    const EXTENSION = '.php';

    protected static $_instance = null;

    public function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new Autoloader();
        }

        return self::$_instance;
    }


    public function load($class_name)
    {
        $relative_path = $this->getRelativePath($class_name);
        if (file_exists(LIB_FOLDER . $relative_path)) {
            require LIB_FOLDER . $relative_path;
        } elseif (file_exists(APP_LIB_FOLDER . $relative_path)) {
            require APP_LIB_FOLDER . $relative_path;
        } else {
            $plugins = scandir(PLUGINS_FOLDER);
            foreach ($plugins as $plugin) {
                $plugin = trim($plugin);
                if (strpos($plugin, '.') !== 0 && is_dir(PLUGINS_FOLDER . $plugin)) {
                    $include = PLUGINS_FOLDER . $plugin . DS . 'lib' . DS . $relative_path;
                    if (file_exists($include)) {
                        require $include;
                        break;
                    }
                }
            }
        }
    }

    public function getRelativePath($class_name)
    {
        $filename = $class_name.self::SUFFIX.self::EXTENSION;
        if ($this->isHelperClass($class_name)) {
            return 'helper'.DS.$filename;
        }elseif($this->isBaseClass($class_name)){
            return 'base'.DS.$filename;
        }else{
            return $filename;
        }
    }

    public function isHelperClass($class_name)
    {
        $needle = 'Helper';
        $length = strlen($needle);
        $start  = $length * -1;

        return (substr($class_name, $start) === $needle);
    }

    public function isBaseClass($class_name)
    {
        $needle = 'Base';
        $length = strlen($needle);

        return (substr($class_name, 0, $length) === $needle);
    }
}
