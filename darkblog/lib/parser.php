<?php
namespace darkblog\lib;

/**
 * Description of parser
 *
 * @author user
 */
class parser {
    
    /**
     * Parse text content into array with values
     * @param type $content
     */
    public static function parse($content) {
        $matches = array();
        
        //preg_match_all('/@(\w+).*?=.*?[\'"](.*?)[\'"]/imsr', $content, $matches, PREG_SET_ORDER);
        //mb_eregi('@(\w+).*?=.*?[\'"](.*?)[\'"]', $content, $matches);
        mb_ereg_search_init($content, '@(\w+)\s*=\s*[\'"](.*?)[\'"]');
        while ($matches = mb_ereg_search_regs()) { //var_dump($matches);
            $result[$matches[1]] = $matches[2];
        }

        //preg_match_all('/@(\w+).*?=.*?([^\s]+)/imsr', $content, $matches, PREG_SET_ORDER);
        //mb_eregi('@(\w+).*?=.*?([^\s]+)', $content, $matches);      
        mb_ereg_search_init($content, '@(\w+)\s*=\s*([^\s]+)');
        while ($matches = mb_ereg_search_regs()) { //var_dump($matches);
            if(!isset($result[$matches[1]]))
                $result[$matches[1]] = $matches[2];
        }

        
        $result['content'] = mb_eregi_replace('@(\w+)\s*=\s*[\'"](.*?)[\'"]', '', $content);
        $result['content'] = mb_eregi_replace('@(\w+)\s*=\s*([^\n^\'^""]+)', '', $result['content']);
        $result['content'] = str_replace("\n\n\n", '', $result['content']);
        $result['content'] = str_replace("\n\n", '', $result['content']);
        $result['content'] = str_replace("\n", '<br/>', $result['content']);
        
        //Links to posts
        $result['content'] = mb_eregi_replace('#(\w+)\s*=\s*[\'"](.*?)[\'"]', '"<a href=\"?name=\\1\">\\2</a>"', $result['content'], 'e');
        $result['content'] = mb_eregi_replace('#(\w+)\s*=\s*([^\n^\'^""]+)', '"<a href=\"?name=\\1\">\\2</a>"', $result['content'], 'e');
        
        //Links to files
        $result['content'] = mb_eregi_replace('\$\$\$(\w+)', '"/file.php?id=\\1"', $result['content'], 'e');
        $result['content'] = mb_eregi_replace('\$\$\$(\w+)', '"/file.php?id=\\1"', $result['content'], 'e');
        
        $result['content'] = mb_eregi_replace('\$(\w+)\s*=\s*[\'"](.*?)[\'"]', '"<a href=\"/file.php?id=\\1\" target=_blank>\\2</a>"', $result['content'], 'e');
        $result['content'] = mb_eregi_replace('\$(\w+)\s*=\s*([^\n^\'^""]+)', '"<a href=\"/file.php?id=\\1\" target=_blank>\\2</a>"', $result['content'], 'e');
        
        //$result['content'] = strip_tags($result['content'], 
        //        "<b><em><i><small><strong><sub><sup><ins><del><mark><img><p><span><h1><h2><h3><h4><h5><h6><h7><h8><h9><ol><ul><li>");
        $result['content'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $result['content']);
        

            //echo "<br/><br/><br/><br/><br/><br/>";
            //var_dump($result['keywords']); 
            
        return $result;
    }
    
    
    /*
     * @q=test    @r =testertert
     *  @w = test @e=test_dfghd-ghdfgh
     * 
     * @ty=" test_dfg hd-ghd $%&#%^$ fgfgfgh  h"
     * 
     * @keywords="Neo,darkblog,privatblog,libertyblog,preamble,anarchy,philosophy,преамбула,анархия,философия"
     * 
     *  @key = EPZ4hrg34TjFuECe5u1kwTLipEbVruJWLH
        @sig = "H+yYeMeMqWk7XRSlsnutLvMEKzpcY1ft7W3yRXe0ntzuJ2ajmTuivIHZflliwyNvh2CdYXy3xBS8QzyV3/2i01s="
        fdhgfdhghd dfghfghfg  fdhfghdfghfg
     * 
     */
    
    public static function nullempty($value) {
        if($value == null) return ''; else return trim($value);
    }
}
