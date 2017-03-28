<?php
namespace darkblog\db;

/**
 * Description of keywords
 *
 * @author user
 */
class keywords extends baseTable {
    public $pk = 'id';
    public $table = '';
    
    public function __construct() {
        $this->table = $this->table_keywords;
    }
    
    public function getIdByKeyword($keyword) {
        $sql = <<<SQL
            SELECT K.$this->pk
            FROM $this->table K
            WHERE K.word = %s
SQL;
        return \DB::queryFirstField($sql, $keyword);
    }    
    
    public function getByKeyword($keyword_id, $lang_id = 0) {
        $condition = '1';
        if($lang_id > 0) $condition = 'K.id IN (SELECT keyword_id FROM '.$this->table_keywords_langs.' WHERE lang_id = '.(int)$lang_id.') ';
        
        $condition2 = '1';
        if($lang_id > 0) $condition2 = 'P.lang_id = '.(int)$lang_id;
        
        $sql = <<<SQL
            SELECT * FROM (SELECT K.*, COUNT(PK.id) AS posts
            FROM $this->table K
            INNER JOIN $this->table_posts_keywords PK ON (PK.keyword_id = K.id)
            INNER JOIN $this->table_posts P ON (PK.post_id = P.id AND $condition2)
            INNER JOIN $this->table_posts_keywords PPK ON (PPK.post_id = P.id)
            WHERE PPK.keyword_id = %i AND $condition
            GROUP BY K.$this->pk) TBL
            ORDER BY posts DESC, word
SQL;
        return \DB::query($sql, $keyword_id);
    } 
    
    public function get($name) {
        $sql = <<<SQL
            SELECT K.*
            FROM $this->table K
            WHERE K.word = %s
SQL;
        return \DB::queryFirstRow($sql, trim($name));
    }
    
    public function selectAll($lang_id = 0) {
        $condition = '1';
        if($lang_id > 0) $condition = 'K.id IN (SELECT keyword_id FROM '.$this->table_keywords_langs.' WHERE lang_id = '.(int)$lang_id.') ';
        
        $condition2 = '1';
        if($lang_id > 0) $condition2 = 'P.lang_id = '.(int)$lang_id;
        
        $sql = <<<SQL
            SELECT * FROM (SELECT K.*, COUNT(P.id) AS posts
            FROM $this->table K
            LEFT JOIN $this->table_posts_keywords PK ON (PK.keyword_id = K.id)
            LEFT JOIN $this->table_posts P ON (PK.post_id = P.id AND $condition2)
            WHERE $condition
            GROUP BY K.$this->pk) TBL
            ORDER BY posts DESC, word
SQL;
        return \DB::query($sql);
    }
    
    public function selectByUser($user_id, $lang_id = 0) {
        $condition = '1';
        if($lang_id > 0) $condition = 'K.id IN (SELECT keyword_id FROM '.$this->table_keywords_langs.' WHERE lang_id = '.(int)$lang_id.') ';
        
        $condition2 = '1';
        if($lang_id > 0) $condition2 = 'P.lang_id = '.(int)$lang_id;
        
        $sql = <<<SQL
            SELECT * FROM (SELECT K.*, COUNT(P.id) AS posts
            FROM $this->table K
            LEFT JOIN $this->table_posts_keywords PK ON (PK.keyword_id = K.id)
            LEFT JOIN $this->table_posts P ON (PK.post_id = P.id AND $condition2)
            WHERE P.user_id = %i AND $condition
            GROUP BY K.$this->pk) TBL
            ORDER BY posts DESC, word
SQL;
        return \DB::query($sql, $user_id);
    }
    
    public function selectByPost($post_id, $lang_id = 0) {
        $condition2 = '1';
        if($lang_id > 0) $condition2 = 'P.lang_id = '.(int)$lang_id;
        
        $sql = <<<SQL
            SELECT * FROM (SELECT K.*, 
                (SELECT COUNT(P.id) FROM $this->table_posts P WHERE $condition2 AND P.id IN (SELECT PPK.post_id FROM $this->table_posts_keywords PPK WHERE PPK.keyword_id = K.id)) AS posts
            FROM $this->table K
            LEFT JOIN $this->table_posts_keywords PK ON (PK.keyword_id = K.id)
            WHERE PK.post_id = %i
            GROUP BY K.$this->pk) TBL
            ORDER BY posts DESC, word
SQL;
        return \DB::query($sql, $post_id);
    }
    
    public function selectByPosts($posts, $lang_id = 0) {
        $posts = implode(', ', $posts);
        
        $condition2 = '1';
        if($lang_id > 0) $condition2 = 'P.lang_id = '.(int)$lang_id;
        
        $sql = <<<SQL
            SELECT K.*, 
                (SELECT COUNT(P.id) FROM $this->table_posts P WHERE $condition2 AND P.id IN (SELECT PPK.post_id FROM $this->table_posts_keywords PPK WHERE PPK.keyword_id = K.id)) AS posts
            FROM $this->table K
            LEFT JOIN $this->table_posts_keywords PK ON (PK.keyword_id = K.id)
            WHERE PK.post_id IN ($posts)
            ORDER BY K.word
SQL;
        return \DB::query($sql);
    }
}