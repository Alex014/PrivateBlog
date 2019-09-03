<?php
namespace darkblog\db;

/**
 * Description of users
 *
 * @author user
 */
class users extends baseTable {
    public $pk = 'id';
    public $table = '';
    
    public function __construct() {
        parent::__construct();
        $this->table = $this->table_users;
    }
    
    public function getIdByName($name) {
        $sql = <<<SQL
            SELECT U.$this->pk
            FROM $this->table U
            WHERE U.username = %s
SQL;
        return $this->queryFirstField($sql, $name);
    }    
    
    public function get($id) {
        $sql = <<<SQL
            SELECT U.*, (SELECT COUNT(PTS.id) FROM $this->table_posts PTS WHERE PTS.user_id = U.id) AS posts
            FROM $this->table U
            WHERE U.$this->pk=%i
            ORDER by posts DESC
SQL;
        return $this->queryFirstRow($sql, $id);
    }
    
    public function getByName($name) {
        $sql = <<<SQL
            SELECT U.*, (SELECT COUNT(PTS.id) FROM $this->table_posts PTS WHERE PTS.user_id = U.id) AS posts
            FROM $this->table U
            WHERE U.username = %s
            ORDER by posts DESC
SQL;
        return $this->queryFirstRow($sql, trim($name));
    } 
    
    public function selectAll($lang_id = 0) {
        $condition = '1';
        if($lang_id > 0) $condition = 'P.lang_id = '.(int)$lang_id;
        $condition2 = '1';
        if($lang_id > 0) $condition2 = 'PTS.lang_id = '.(int)$lang_id;
        
        $sql = <<<SQL
            SELECT U.*, (SELECT COUNT(PTS.id) FROM $this->table_posts PTS WHERE PTS.user_id = U.id AND $condition2) AS posts
            FROM $this->table U
            LEFT JOIN $this->table_posts P ON (P.user_id = U.id)
            WHERE $condition
            GROUP BY U.$this->pk
SQL;
        return pager::pagedQuery($sql);
    }
    
    public function updateLang($pk, $lang_id) {
        $this->update($pk, array('lang_id' => $lang_id));
    }
    
    public function getByKeyword($keyword_id, $lang_id = 0) {
        $keyword_id = (int)$keyword_id;
        $condition = '1';
        if($lang_id > 0) $condition = 'PTS.lang_id = '.(int)$lang_id;
        
        $sql = <<<SQL
            SELECT U.*, (SELECT COUNT(PTS.id) FROM $this->table_posts PTS WHERE PTS.user_id = U.id AND $condition) AS posts
            FROM $this->table U
            LEFT JOIN $this->table_posts P ON (P.user_id = U.id)
            LEFT JOIN $this->table_posts_keywords PK ON (PK.post_id = P.id)
            WHERE PK.keyword_id = $keyword_id
            GROUP BY U.$this->pk
SQL;
        return pager::pagedQuery($sql);
    }
}