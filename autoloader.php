<?php
if(!isset($application)) die("Can not call directly");

class libAutoloader {
    private static $registered = false;
    
    public function __construct() {
        if(!self::$registered) {
            spl_autoload_register(array($this, 'loader'), true, true);
            self::$registered = true;
        }
    }
    
    private function loader($className) {
        $path = explode('\\', $className);
        $path = implode('/', $path);
        $fileName = __DIR__.'/'.$path.'.php';

        require_once $fileName;
        return true;
    }
}
