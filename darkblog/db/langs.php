<?php
namespace darkblog\db;

/**
 * Description of langs
 *
 * @author user
 */
class langs extends baseTable {
    public $pk = 'id';
    public $table = '';
    
    public function __construct() {
        parent::__construct();
        $this->table = $this->table_langs;
    }
    
    public function getIdByName($name) {
        $sql = <<<SQL
            SELECT L.$this->pk
            FROM $this->table L
            WHERE L.name = %s
SQL;
        return \DB::queryFirstField($sql, $name);
    }    
    
    public function get($id) {
        $sql = <<<SQL
            SELECT L.*, COUNT(P.id) AS posts
            FROM $this->table L
            LEFT JOIN $this->table_posts P ON (P.lang_id = L.id)
            WHERE L.$this->pk=%i
SQL;
        return \DB::queryFirstRow($sql, $id);
    }
    
    public function selectAll() {
        $sql = <<<SQL
            SELECT * FROM (SELECT L.*, COUNT(P.id) AS posts
            FROM $this->table L
            LEFT JOIN $this->table_posts P ON (P.lang_id = L.id)
            GROUP BY L.$this->pk) TBL
            ORDER BY name
SQL;
        return \DB::query($sql);
    }
    
    public function selectOrderByName() {
        return $this->select();
    }
    
    public function selectOrderByPosts() {
        $sql = <<<SQL
            SELECT * FROM (SELECT L.*, COUNT(P.id) AS posts
            FROM $this->table L
            LEFT JOIN $this->table_posts P ON (P.lang_id = L.id)
            GROUP BY L.$this->pk) TBL
            ORDER BY posts
SQL;
        return \DB::query($sql);
    }
}