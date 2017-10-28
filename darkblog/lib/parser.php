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
        
        //Scripts
        $result['content'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $result['content']);
            
        return $result;
    }
    
    /**
     * Link all parsed posts
     * @param type $posts
     * @param type $content
     * @param type $levels
     */
    public static function link(&$posts, $post, $levels = 5) {
        $results = array();
        
        if($levels > 0) {
            
            $regex_posts = array();
            mb_ereg_search_init($post['content'], '%%(.*?)%%');
            
            while ($matches = mb_ereg_search_regs()) {
                $regex_posts[$matches[1]] = $matches[0];
            }
            
            if(!empty($regex_posts)) { 
                foreach ($regex_posts as $post_name => $regex) {
                    if(isset($posts[$post_name])) { //var_dump($post_name);
                        $posts[$post_name] = self::link($posts, $posts[$post_name], $levels - 1);
                        $results[$regex] = $posts[$post_name]['content'];
                    }
                }
            }

            if(!empty($results)) {
                foreach ($results as $post_name => $post_content) {
                    $post['content'] = mb_eregi_replace($post_name, $post_content, $post['content']);
                }
            }
            
        }
            
        $post['content'] = mb_eregi_replace('%%.*?%%', '', $post['content']);
        
        return $post;
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
