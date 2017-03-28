<?php
namespace darkblog\db;

/**
 * Description of pager
 *
 * @author user
 */
class pager {
    
    public static $total;
    public static $pages_count;
    public static $pages;
    public static $per_page = 10;
    public static $interval = 2;
    public static $page = 1;
    
    public static $from;
    public static $order = 'id DESC';
    
    public static function calc_pages($total) {
        self::$pages = array();
        self::$pages_count = ceil($total / self::$per_page);
        if(self::$pages_count == 0) self::$pages_count = 1;

        if(self::$page > self::$pages_count) self::$page = self::$pages_count;
        self::$pages['page'] = self::$page;
        self::$pages['total'] = self::$pages_count;
        self::$pages['per_page'] = self::$per_page;
        
        self::$from = (self::$page - 1) * self::$per_page;

        if(self::$page > 1) self::$pages['prev'] = self::$page - 1; 
        if(self::$page < self::$pages_count) self::$pages['next'] = self::$page + 1;
        if((self::$page - self::$interval) > 1) self::$pages['first'] = 1;
        if((self::$page + self::$interval) < self::$pages_count) self::$pages['last'] = self::$pages_count;

        self::$pages['list'] = array();
        $f = self::$page - self::$interval;
        if($f < 1) $f = 1;
        $l = self::$page + self::$interval;
        if($l > self::$pages_count) $l = self::$pages_count;

        for($p = $f; $p <= $l; $p++) {
            self::$pages['list'][] = $p;
        }
    }
    
    public static function query_count($sql) {
        $sql = "SELECT COUNT(*) AS cnt FROM ( $sql ) TBL";
        return \DB::queryFirstField($sql);
    }
    
    public static function query_limit($sql) {
        $sql = "SELECT * FROM ( $sql ) TBL ORDER BY ".self::$order." LIMIT ".self::$from.", ".self::$per_page;
        return \DB::query($sql);
    }
    
    public static function pagedQuery($sql) {
        self::$total = self::query_count($sql);
        self::calc_pages(self::$total);
        return self::query_limit($sql);
    }
    
}