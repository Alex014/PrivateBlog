<?php
namespace darkblog\db;

/**
 * Description of posts_keywords
 *
 * @author user
 */
class posts_keywords extends baseTable {
    public $pk = 'id';
    public $table = '';
    
    public function __construct() {
        parent::__construct();
        $this->table = $this->table_posts_keywords;
    }
    
    public function insert_pk($keyword_id, $post_id) {
        $this->insertIgnore(array('keyword_id' => $keyword_id, 'post_id' => $post_id));
    }
}