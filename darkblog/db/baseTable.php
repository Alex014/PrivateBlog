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
    
    public $table_posts;
    public $table_keywords;
    public $table_posts_keywords;
    public $table_users;
    public $table_langs;
    public $table_keywords_langs;
    
    public function __construct() {
        $this->table_posts = DB_PREFIX.'posts';
        $this->table_keywords = DB_PREFIX.'keywords';
        $this->table_posts_keywords = DB_PREFIX.'posts_keywords';
        $this->table_users = DB_PREFIX.'users';
        $this->table_langs = DB_PREFIX.'langs';
        $this->table_keywords_langs = DB_PREFIX.'keywords_langs';
        
        if(SQLITE) {
            $this->db = \config::$db;
        }
    }
    
    public function get($condition) {
        if(is_array($condition)) {
            if(SQLITE) {
                $acondition = array();
                foreach ($condition as $key => $value) {
                    $acondition[] = "$key = ".$this->escape($value);
                }
                $scondition = implode(' AND ', $acondition);
                return $this->db->querySingle("SELECT * FROM $this->table WHERE $scondition", TRUE);
            } else {
                $acondition = array();
                foreach ($condition as $key => $value) {
                    $acondition[] = "$key = %?$key";
                }
                $scondition = implode(' AND ', $acondition);
                return \DB::queryFirstRow("SELECT * FROM `$this->table` WHERE $scondition", $condition);
            }
        }
        else {
            if(SQLITE) {
                return $this->db->querySingle("SELECT * FROM `$this->table` WHERE $this->pk=".$this->escape($condition), TRUE);
            }
            else {
                return \DB::queryFirstRow("SELECT * FROM $this->table WHERE $this->pk=%s", $condition);
            }
        }
    }
    
    public function escape($value) {
        if(SQLITE) {
            return "'".$this->db->escapeString($value)."'";
        }
        else {
            return '"'.\DB::getMDB()->get()->escape_string($value).'"';
        }
    }
    
    public function query($sql) {
        $args = func_get_args(); 
        
        if(SQLITE) {
            $q = $this->db->query( $this->_parse_sql_params($sql, $args) );
            $result = array();
            while ($row = $q->fetchArray()) {
                $result[] = $row;
            }

            return $result;
        }
        else {
            //return \DB::query($sql);
            return call_user_func_array(array('\DB', 'query'), $args); 
        }
    }
    
    public function select($condition) {
        if(SQLITE) {
                $acondition = array();
                foreach ($condition as $key => $value) {
                    $acondition[] = "$key = ".$this->escape($value);
                }
                $scondition = implode(' AND ', $acondition);
                $q = $this->db->query("SELECT * FROM $this->table WHERE $scondition");
                
                $result = array();
                while ($row = $q->fetchArray()) {
                    $result[] = $row;
                }
                
                return $this->query("SELECT * FROM $this->table WHERE $scondition");
        }
        else {
            $acondition = array();
            foreach ($condition as $key => $value) {
                $acondition[] = "$key = %?$key";
            }
            $scondition = implode(' AND ', $acondition);
            return \DB::query("SELECT * FROM `$this->table` WHERE $scondition", $condition);
        }
    }
    
    private function _parse_sql_params($sql, $params) {
        if(count($params) > 1) {
            for($i = 1; $i < count($params); $i++) {
                if(is_int($params[$i])) {
                    $sql = str_replace('%i', "'".(int)$params[$i]."'", $sql);
                }
                if(is_double($params[$i])) {
                    $sql = str_replace('%d', "'".(double)$params[$i]."'", $sql);
                }
                elseif(is_float($params[$i])) {
                    $sql = str_replace('%f', "'".(float)$params[$i]."'", $sql);
                }
                elseif(is_string($params[$i])) {
                    $sql = str_replace('%s', $this->escape($params[$i]), $sql);
                }
            }
        }
        return $sql;
    }
    
    public function queryFirstRow($sql) {
        $args = func_get_args(); 
            
        if(SQLITE) {
            return $this->db->querySingle($this->_parse_sql_params($sql, $args), TRUE);
        }
        else {
            //return \DB::queryFirstRow($sql);  
            return call_user_func_array(array('\DB', 'queryFirstRow'), $args); 
        }
    }
    
    public function queryFirstField($sql) {
        $args = func_get_args(); 
        
        if(SQLITE) {
            return $this->db->querySingle($this->_parse_sql_params($sql, $args), FALSE);
        }
        else {
            //return \DB::queryFirstField($sql);  
            return call_user_func_array(array('\DB', 'queryFirstField'), $args); 
        }
    }
    
    public function insert($row) {
        if(SQLITE) {
            $columns = array();
            $values = array();
            foreach($row as $col => $value) {
                $columns[] = $col;
                $values[] = $this->escape($value);
            }
            $columns = implode(' , ', $columns);
            $values = implode(' , ', $values);
            $sql = "INSERT INTO $this->table ($columns) VALUES ($values)";
            return $this->db->query($sql);
        } else {
            \DB::insert($this->table, $row);
        }
    }
    
    public function insertIgnore($row) {
        if(SQLITE) {
            $columns = array();
            $values = array();
            foreach($row as $col => $value) {
                $columns[] = $col;
                $values[] = $this->escape($value);
            }
            $columns = implode(' , ', $columns);
            $values = implode(' , ', $values);
            $sql = "INSERT OR IGNORE INTO $this->table ($columns) VALUES ($values)";
            return $this->db->query($sql);
        } else {
            \DB::insertIgnore($this->table, $row);
        }
    }
    
    public function insertUpdate($row) {
        if(SQLITE) {
            $columns = array();
            $values = array();
            foreach($row as $col => $value) {
                $columns[] = $col;
                $values[] = $this->escape($value);
            }
            $columns = implode(' , ', $columns);
            $values = implode(' , ', $values);
            $sql = "REPLACE INTO $this->table ($columns) VALUES ($values)";
            return $this->db->query($sql);
        } else {
            \DB::insertUpdate($this->table, $row);
        }
    }
    
    public function update($pk, $row) {
        if(SQLITE) {
            $values = array();
            foreach($row as $col => $value) {
                $values[] = "$col = ".$this->escape($value);
            }
            $values = implode(' , ', $values);
            $sql = "UPDATE $this->table SET $values WHERE $this->pk = ".$this->escape($pk);
            return $this->db->query($sql);
        } else {
            \DB::update($this->table, $row, "$this->pk=%s", $pk);
        }
    }
    
    public function sqlInsertValue($sql, $marker, $value) {
        if($marker == '%i')
            return str_replace($marker, (int)$value, $sql);
        if($marker == '%f')
            return str_replace($marker, (float)$value, $sql);
        if($marker == '%s')
            return str_replace($marker, $this->escape($value), $sql);
    }
    
    public function delete($pk) {
        if(SQLITE) {
            $this->db->query("DELETE FROM $this->table WHERE $this->pk=".$this->escape($pk));
        } else {
            \DB::delete($this->table, "$this->pk=%s", $pk);
        }
    }
    
    public function clear() {
        if(SQLITE) {
            $this->db->query("DELETE FROM $this->table");
        } else {
            \DB::query("DELETE FROM `$this->table`");
        }
    }
    
    public function insertId() {
        if(SQLITE) {
            return $this->db->lastInsertRowID();
        } else {
            return \DB::insertId();
        }
    }
    
    public function affectedRows() {
        if(SQLITE) {
            return $this->db->changes();
        } else {
            return \DB::affectedRows();
        }
    }
}