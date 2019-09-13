<?php
namespace darkblog\objects;

/**
 * Description of keywords
 *
 * @author user
 */
class keywords {
    
    public function __construct() {
        $olang = new \darkblog\db\langs();
        $this->lang_id = $olang->getIdByName($_SESSION['lang']);
        var_dump($_SESSION['lang'], $this->lang_id);
    }
    
    public function getKeyword($keyword_id) {
        $okeywords = new \darkblog\db\keywords();
        $keyword = $okeywords->get($keyword_id);
        return $keyword;
    }
    
    public function getKeywordByName($name) {
        $okeywords = new \darkblog\db\keywords();
        $keyword = $okeywords->get($name);
        return $keyword;
    }
    
    public function getByKeyword($keyword_id) {
        $okeywords = new \darkblog\db\keywords();
        $lang_id = $this->lang_id;
        return $okeywords->getByKeyword($keyword_id, $lang_id);
    }
    
    public function getKeywords() {
        $okeywords = new \darkblog\db\keywords();
        $lang_id = $this->lang_id;
        return $okeywords->selectAll($lang_id);
    }
    
    /**
     * 
     * @param \darkblog\db\keywords $keywords array();
     */
    public function insertKeywords($keywords) {
        $okeywords = new \darkblog\db\keywords();
        
        $okeywords->clear();
        foreach ($keywords as $keyword) {
            $okeywords->insertIgnore(array('keyword' => $keyword));
            $id_list[] = $okeywords->insertId();
        }
        
        return $id_list;
    }
}