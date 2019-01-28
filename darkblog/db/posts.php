<?php
namespace darkblog\db;

/**
 * Description of posts
 *
 * @author user
 */
class posts extends baseTable {
    public $pk = 'id';
    public $table = '';
    
    public function __construct() {
        parent::__construct();
        $this->table = $this->table_posts;
    }
    
    public function updateLang($pk, $lang_id) {
        $this->update($pk, array('lang_id' => $lang_id));
    }
    
    public function updateVerified($pk) {
        $this->update($pk, array('v' => 1));
    }
    
    public function updateUser($pk, $user_id) {
        $this->update($pk, array('user_id' => $user_id));
    }
    
    public function updateReplyPost($pk, $reply_id) {
        $this->update($pk, array('reply_id' => $reply_id));
    }
    
    public function updateMetadata($pk, $metadata) {
        $this->update($pk, array('metadata' => $metadata));
    }
    
    public function get($id) {
        $sql = <<<SQL
            SELECT P.*, U.username, U.key AS userkey
            FROM $this->table P
            LEFT JOIN $this->table_users U ON (P.user_id = U.id)
            WHERE P.$this->pk=%i
SQL;
        return \DB::queryFirstRow($sql, $id);
    }
    
    public function getByName($name) {
        $sql = <<<SQL
            SELECT P.*, U.username, U.key AS userkey
            FROM $this->table P
            LEFT JOIN $this->table_users U ON (P.user_id = U.id)
            WHERE P.name=%s
SQL;
        return \DB::queryFirstRow($sql, trim($name));
    }
    
    public function getIdByName($name) {
        $sql = <<<SQL
            SELECT P.$this->pk
            FROM $this->table P
            WHERE P.name = %s
SQL;
        return \DB::queryFirstField($sql, $name);
    }    
    
    public function getByKeyword($keyword_id, $lang_id = 0) {
        $keyword_id = (int)$keyword_id;
        $condition = '1';
        if($lang_id > 0) $condition = 'P.lang_id = '.(int)$lang_id;
        
        $sql = <<<SQL
            SELECT P.*
            FROM $this->table P 
            LEFT JOIN $this->table_posts_keywords PK ON (PK.post_id = P.id)
            WHERE PK.keyword_id = $keyword_id AND $condition
SQL;
        return pager::pagedQuery($sql);
    }
    
    public function getReplies($post_id) {
        $post_id = (int)$post_id;
        $sql = <<<SQL
            SELECT P.*
            FROM $this->table P 
            WHERE P.reply_id = $post_id
SQL;
        return pager::pagedQuery($sql);
    }
    
    public function selectAll($paged = true) {
        $sql = <<<SQL
            SELECT *
            FROM $this->table
SQL;
        if($paged)
            return pager::pagedQuery($sql);
        else
            return \DB::query ($sql);
    }
    
    public function selectByUser($user_id, $lang_id = 0) {
        $user_id = (int)$user_id;
        $condition = '1';
        if($lang_id > 0) $condition = 'P.lang_id = '.(int)$lang_id;
        
        $sql = <<<SQL
            SELECT P.*, U.key AS userkey
            FROM $this->table P
            LEFT JOIN $this->table_users U ON (P.user_id = U.id)
            WHERE P.user_id=$user_id AND $condition
SQL;
        return pager::pagedQuery($sql);
    }
    
    public function selectByLang($lang_id) {
        $lang_id = (int)$lang_id;
        $sql = <<<SQL
            SELECT P.*, U.key AS userkey
            FROM $this->table P
            LEFT JOIN $this->table_users U ON (P.user_id = U.id)
            WHERE P.lang_id=$lang_id
SQL;
        return pager::pagedQuery($sql);
    }
    
    public function selectByKeywords($keywords) {
        $keywords = implode(', ', $keywords);
        
        $sql = <<<SQL
            SELECT P.*, U.key AS userkey
            FROM $this->table P
            LEFT JOIN $this->table_users U ON (P.user_id = U.id)
            LEFT JOIN $this->table_posts_keywords PK ON (PK.post_id = P.id)
            WHERE PK.keyword_id IN ($keywords)
            GROUP BY P.id) TBL
            ORDER BY posts DESC
SQL;
        return pager::pagedQuery($sql);
    }
    
    public function selectByTitle($title, $lang_id) {
        $lang_id = (int)$lang_id;
        $condition = '1';
        if($lang_id > 0) $condition = 'P.lang_id = '.(int)$lang_id;
        
        $title = $this->escape('%'.$title.'%');
        $sql = <<<SQL
            SELECT P.*, U.key AS userkey
            FROM $this->table P
            LEFT JOIN $this->table_users U ON (P.user_id = U.id)
            WHERE $condition AND P.title LIKE $title OR P.name LIKE $title
SQL;
        //echo $sql;
        return pager::pagedQuery($sql);
    }
    
    public function selectByContent($content_like) {
        $content_like = $this->escape('%'.$content_like.'%');
        $sql = <<<SQL
            SELECT P.*, U.key AS userkey
            FROM $this->table P
            LEFT JOIN $this->table_users U ON (P.user_id = U.id)
            WHERE P.content LIKE $content_like
SQL;
        return pager::pagedQuery($sql);
    }
    
    public function selectByContentMultiple($words_and, $words_or, $lang_id) {
        $lang_id = (int)$lang_id;
        $scondition = '1';
        if($lang_id > 0) $scondition = 'P.lang_id = '.(int)$lang_id;
        
        $_sql = <<<SQL
            SELECT P.*, U.key AS userkey
            FROM $this->table P
            LEFT JOIN $this->table_users U ON (P.user_id = U.id)
            WHERE $scondition
SQL;
        
        if(!empty($words_and)) {
            foreach ($words_and as $word) {
                $word = $this->escape("%$word%");
                $condition[] = " (P.title LIKE $word OR P.name LIKE $word OR P.content LIKE $word) ";
            }
            $condition = implode(' AND ', $condition);
            $sql[] = $_sql . ' AND ' . $condition;
        }

        if(!empty($words_or)) {
            foreach ($words_or as $word) {
                $word = $this->escape("%$word%");
                $condition[] = " (P.title LIKE $word OR P.name LIKE $word OR P.content LIKE $word) ";
            }
            $condition = implode(' OR ', $condition);
            $sql[] = $_sql . ' AND ' . $condition;
        }

        if(!empty($sql)) {
            $sql = implode(" \n UNION \n ", $sql);
            //echo $sql;
            return pager::pagedQuery($sql);
        }
        else {
            return false;
        }
    }
    
    public function selectByContentRegexp($content_regext, $lang_id) {
        $post_id = (int)$post_id;
        $content_regext = $this->escape($content_regext);
        $condition = '1';
        if($lang_id > 0) $condition = 'P.lang_id = '.(int)$lang_id;
        
        $sql = <<<SQL
            SELECT P.*, U.key AS userkey
            FROM $this->table P
            LEFT JOIN $this->table_users U ON (P.user_id = U.id)
            WHERE $condition AND P.content REGEXP $content_regext
SQL;
        return pager::pagedQuery($sql);
    }
}