<?php
namespace darkblog\other;

/**
 * Description of url
 *
 * @author user
 */
class url {
    public static $parts;
    
    public static function parse() {
        self::$parts = array();
        
        foreach ($_GET as $key => $value) {
            self::$parts[$key] = $value;
        }
    }
    
    public static function build($params) {  
        $result = array();
        
        foreach (self::$parts as $key => $value) {
            $result[$key] = $value;
        }
        
        foreach ($params as $key => $value) {
            $result[$key] = $value;
        }
        
        return '?'.http_build_query($result);
    }
}
