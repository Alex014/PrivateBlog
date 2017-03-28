<?php
namespace darkblog\db;

/**
 * Description of base_table
 *
 * @author user
 */
class baseTable {
    public $table = '';
    public $pk = '';
    public $insert_id = '';
    
    public $table_posts = DB_PREFIX.'posts';
    public $table_keywords = DB_PREFIX.'keywords';
    public $table_posts_keywords = DB_PREFIX.'posts_keywords';
    public $table_users = DB_PREFIX.'users';
    public $table_langs = DB_PREFIX.'langs';
    public $table_keywords_langs = DB_PREFIX.'keywords_langs';
    
    public function get($condition) {
        if(is_array($condition)) {
            $acondition = array();
            foreach ($condition as $key => $value) {
                $acondition[] = "$key = %?$key";
            }
            $scondition = implode(' AND ', $acondition);
            return \DB::queryFirstRow("SELECT * FROM `$this->table` WHERE $scondition", $condition);
        }
        else {
            return \DB::queryFirstRow("SELECT * FROM `$this->table` WHERE $this->pk=%s", $condition);
        }
    }
    
    public function escape($value) {
        return '"'.mysql_escape_string($value).'"';
    }
    
    public function select($condition) {
        $acondition = array();
        foreach ($condition as $key => $value) {
            $acondition[] = "$key = %?$key";
        }
        $scondition = implode(' AND ', $acondition);
        return \DB::query("SELECT * FROM `$this->table` WHERE $scondition", $condition);
    }
    
    public function insert($row) {
        \DB::insert($this->table, $row);
    }
    
    public function insertIgnore($row) {
        \DB::insertIgnore($this->table, $row);
    }
    
    public function insertUpdate($row) {
        \DB::insertUpdate($this->table, $row);
    }
    
    public function update($pk, $row) {
        \DB::update($this->table, $row, "$this->pk=%s", $pk);
    }
    
    public function delete($pk) {
        \DB::delete($this->table, "$this->pk=%s", $pk);
    }
    
    public function clear() {
        \DB::query("DELETE FROM `$this->table`");
    }
    
    public function insertId() {
        return \DB::insertId();
    }
    
    public function affectedRows() {
        return \DB::affectedRows();
    }
}