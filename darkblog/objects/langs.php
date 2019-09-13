<?php
namespace darkblog\objects;

/**
 * Description of langs
 *
 * @author user
 */
class langs {
    
    public function insertLangs($langs) {
        $olangs = new \darkblog\db\langs();
        
        $olangs->clear();
        foreach ($langs as $lang) {
            $olangs->insertIgnore(array('name' => $lang));
        }
    }
    
    public function selectAll() {
        $olangs = new \darkblog\db\langs();
        
        return $olangs->selectAll();
    }
    
    public function getIdByName($name) {
        $olangs = new \darkblog\db\langs();
        
        return $olangs->getIdByName($name);
    }
}