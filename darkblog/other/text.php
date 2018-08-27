<?php
/**
 * Description of text
 *
 * @author user
 */
class text {
    public static function cut($text, $length, $empty = ' -[No name]- ') {
        $text = trim(strip_tags($text));
        $_text = '';
                
        if(empty($text) || mb_strlen($text) == 0)
            return $empty;
        
        if(mb_strlen($text) < $length)
            return $text;
        
        $atext = mb_split(' ', $text);
        
        $_text .= $atext[0];
        
        if(mb_strlen($_text) > $length) {
            return mb_strcut($_text, 0, $length - 4). ' ...';
        }
        
        $index  = 1;
        while((mb_strlen($_text) + mb_strlen($atext[$index]) + 1) < $length) {
            $_text .= ' '.$atext[$index];
            $index ++;
        }
        
        if(($index + 1) < count($atext)) $_text .= ' ...';
        
        return $_text;
    }
}
