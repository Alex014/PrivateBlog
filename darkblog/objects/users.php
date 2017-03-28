<?php
namespace darkblog\objects;

/**
 * Description of users
 *
 * @author user
 */
class users {
    
    public function getUser($user_id) {
        $ouser = new \darkblog\db\users();
        $okeywords = new \darkblog\db\keywords();
        $oposts = new \darkblog\db\posts();
        
        $user = $ouser->get($user_id);
        $lang_id = $_SESSION['lang'];
        $user['keywords'] = $okeywords->selectByUser($user_id, $lang_id);
        $user['users_posts'] = $oposts->selectByUser($user_id, $lang_id);
        
        return $user;
    }
    
    public function getUserByName($name) {
        $ouser = new \darkblog\db\users();
        $okeywords = new \darkblog\db\keywords();
        $oposts = new \darkblog\db\posts();
        
        $user = $ouser->getByName($name);
        $lang_id = $_SESSION['lang'];
        $user['keywords'] = $okeywords->selectByUser($user['id'], $lang_id);
        $user['users_posts'] = $oposts->selectByUser($user['id'], $lang_id);
        
        return $user;
    }
    
    public function getByKeyword($keyword_id) {
        $ouser = new \darkblog\db\users();
        $lang_id = $_SESSION['lang'];
        return $ouser->getByKeyword($keyword_id, $lang_id);
    }
    
    public function getUsers() {
        $ousers = new \darkblog\db\users();
        $lang_id = $_SESSION['lang'];
        return $ousers->selectAll($lang_id, $lang_id);
    }
    
    public function insertUsers($users) {
        $ousers = new \darkblog\db\users();
        
        foreach ($users as $user) {            
            $ousers->insertIgnore($user);
        }
    }
    
    public function clearUsers($users) {
        $ousers = new \darkblog\db\users();
        $ousers->clear();
    }
    
    public function blockchanGetUsers() {
        $users = \darkblog\lib\emercoin::name_filter('^blogger:.+', 0, 0, 0);
        $result_users = array();
        foreach ($users as $user) {
            $username = explode(':', $user['name']);
            $username = array_slice($username, 1);
            if(count($username)) {
                $result_users[implode(':', $username)] = \darkblog\lib\parser::parse($user['value']);
            }
        }

        return $result_users;
    }
    
    /**
     * 
     * @param type $userkey
     * @param type $usersig
     * @param type $username
     * @return type
     */
    public function blockchanVerifyUser($userkey, $usersig, $username) {
        return \darkblog\lib\emercoin::verifymessage($userkey, $usersig, $username);
    }
    
    public function importUsers() {
        $users = $this->blockchanGetUsers();
        $insert_users = array();
        


        foreach ($users as $username => $user) {
            if(isset($user['key']) && isset($user['sig']) && isset($user['content'])) {
                if($this->blockchanVerifyUser($user['key'], $user['sig'], $username)) {
                    $insert_users[] = array(
                        'key' => \darkblog\lib\parser::nullempty($user['key']),
                        'username' => $username,
                        'sig' => \darkblog\lib\parser::nullempty($user['sig']),
                        'descr' => \darkblog\lib\parser::nullempty($user['content'])
                    );
                }
            }
        }
        //var_dump($insert_users);
        $this->insertUsers($insert_users);
    }
}