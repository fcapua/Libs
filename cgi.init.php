<?php
#AUTOLOAD CLASSES
function __autoload($class_name) {
    require LIB_FOLDER.$class_name.'.class.php';
}
