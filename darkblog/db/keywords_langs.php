<?php
namespace darkblog\db;

/**
 * Description of keywords_langs
 *
 * @author user
 */
class keywords_langs extends baseTable {
    public $pk = 'id';
    public $table = '';
    
    public function __construct() {
        parent::__construct();
        $this->table = $this->table_keywords_langs;
    }
    
    public function insert_pk($keyword_id, $lang_id) {
        $this->insertIgnore(array('keyword_id' => $keyword_id, 'lang_id' => $lang_id));
    }
}