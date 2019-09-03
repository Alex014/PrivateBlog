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
    
    public function getMyUsers() {
        $bloggers = \darkblog\lib\emercoin::name_list_filtered("blogger:", 'base64');
        
        return array_map(function($blogger) {
            $blogger['value'] = base64_decode($blogger['value']);
            $name = explode(':', $blogger['name']);
            $blogger['name'] = $name[1];
            
            $vars = \darkblog\lib\parser::parse($blogger['value']);
            $blogger['content'] = $vars['content'];
            $blogger['key'] = $vars['key'];
            $blogger['sig'] = $vars['sig'];
            
            return $blogger;
        }, $bloggers);
    }
    
    
    public function signBlogger($username, $userkey) {
        return \darkblog\lib\emercoin::signmessage($userkey, $username);
    }
    
    /**
     * 
     * @param type $username
     * @param type $post_name
     * @param type $userkey
     * @return type
     */
    public function signPost($username, $post_name, $userkey) {
        return \darkblog\lib\emercoin::signmessage($userkey, "$username:$post_name");
    }
    
    public function newUser($name, $data, $days) {
        $name = 'blogger:'.$name;
        $value = '';
        
        if(!empty($data['descr'])) {
            $value = $data['descr'];
        }
        
        if(!empty($data['key']) && !empty($data['sig'])) {
            $value = $value.' @key="'.$data['key'].'"'.' @sig="'.$data['sig'].'"';
        }
        
        \darkblog\lib\emercoin::name_new($name, $value, $days);
    }
    
    public function editUser($name, $data, $days) {
        $name = 'blogger:'.$name;
        $value = '';
        
        if(!empty($data['descr'])) {
            $value = $data['descr'];
        }
        
        if(!empty($data['key']) && !empty($data['sig'])) {
            $value = $value.' @key="'.$data['key'].'"'.' @sig="'.$data['sig'].'"';
        }
        
        \darkblog\lib\emercoin::name_update($name, $value, $days);
    }
    
    public function deleteUser($name) {
        $name = 'blogger:'.$name;
        
        \darkblog\lib\emercoin::name_delete($name);
    }
    
    public function getUserData($name) {
        $full_name = 'blogger:'.$name;
        //var_dump($name);
        $dt = \darkblog\lib\emercoin::name_show($full_name);
        //var_dump($dt);
        $data = \darkblog\lib\parser::parse($dt['value'], false);
        //var_dump($data);
        $data['username'] = $name;
        $data['time'] = $dt['time'];
        $data['expires_in'] = $dt['expires_in'];
        
        $data['_days'] = $dt['expires_in']/175;
        $post['expired'] = ($post['expires_in'] < 0);
        
        if($data['expires_in'] > 0) {
            $data['created'] = date('Y-m-d H:i:s', $data['time']);
            $data['expires'] = date('Y-m-d H:i:s', $data['time'] + round($data['_days']*86400));
        }
        else {
            $data['created'] = date('Y-m-d H:i:s', $data['time']);
            $data['expires'] = date('Y-m-d H:i:s', time() + round($data['_days']*86400));
        }
        
        $data['days'] = ceil($dt['expires_in']/175);
        if($data['days'] < 1) $data['days'] = 1;
        
        $data['content'] = trim($data['content']);
        //var_dump($data);
        return $data;
    }
}