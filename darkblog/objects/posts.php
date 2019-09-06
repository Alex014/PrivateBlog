<?php
namespace darkblog\objects;

/**
 * Description of posts
 *
 * @author user
 */
class posts {
    
    public function getPost($post_id) {
        $oposts = new \darkblog\db\posts();
        $okeywords = new \darkblog\db\keywords();
        $ousers = new \darkblog\db\users();
        
        $post = $oposts->get($post_id);
        $post['_keywords'] = $post['keywords'];
        $lang_id = $_SESSION['lang'];
        $post['keywords'] = $okeywords->selectByPost($post_id, $lang_id);
        if(!empty($post['user_id']))
            $post['user'] = $ousers->get($post['user_id']);
        
        return $post;
    }
    
    public function getPostByName($post_name) {
        $oposts = new \darkblog\db\posts();
        $okeywords = new \darkblog\db\keywords();
        $ousers = new \darkblog\db\users();
        $post = $oposts->getByName($post_name);
        $post['_keywords'] = $post['keywords'];
        $lang_id = $_SESSION['lang'];
        
        if(!empty($post['id']))
            $post['keywords'] = $okeywords->selectByPost($post['id'], $lang_id);

        if(!empty($post['user_id']))
            $post['user'] = $ousers->get($post['user_id']);
        
        return $post;
    }
    
    public function getByKeyword($keyword_id) {
        $oposts = new \darkblog\db\posts();
        $lang_id = $_SESSION['lang'];
        return $oposts->getByKeyword($keyword_id, $lang_id);
    }
    
    public function getReplies($post_id) {
        $oposts = new \darkblog\db\posts();
        return $oposts->getReplies($post_id);
    }
    public function getRepliesFull($post_id) {
        $oposts = new \darkblog\db\posts();
        $replies = $oposts->getReplies($post_id);
        
        function replies_recursive(&$replies) {
            $oposts = new \darkblog\db\posts();
        
            foreach($replies as $index => $reply) {
                $replies[$index]['children'] = $oposts->getReplies($reply['id']);
                if(!empty($replies[$index]['children']))
                    replies_recursive($replies[$index]['children']);
            }
        }
        
        replies_recursive($replies);
        
        return $replies;
        
    }
    
    public function searchPostsByTitle($title) {
        $oposts = new \darkblog\db\posts();
        $okeywords = new \darkblog\db\keywords();
        
        $lang_id = $_SESSION['lang'];
        $posts = $oposts->selectByTitle($title, $lang_id);
        
        $post_ids = array();
        foreach ($posts as $post) {
            $post_ids[] = $post['id'];
        }
        
        if(!empty($post_ids))
            $_keywords = $okeywords->selectByPosts($post_ids, $lang_id);
        else
            $_keywords = array();
        
        //pARSING KEYWORDS
        foreach ($_keywords as $keyword) {
            $keywords[$keyword['id']] = $keyword;
        }
        
        return array('posts' => $posts, 'keywords' => $keywords);
    }
    
    public function searchPostsByContent($regexp) {
        $oposts = new \darkblog\db\posts();
        $okeywords = new \darkblog\db\keywords();
        
        $lang_id = $_SESSION['lang'];
        $posts = $oposts->selectByContentRegexp($regexp, $lang_id);
        
        $post_ids = array();
        foreach ($posts as $post) {
            $post_ids[] = $post['id'];
        }
        
        if(!empty($post_ids))
            $_keywords = $okeywords->selectByPosts($post_ids, $lang_id);
        else
            $_keywords = array();
        
        //pARSING KEYWORDS
        foreach ($_keywords as $keyword) {
            $keywords[$keyword['id']] = $keyword;
        }
        
        return array('posts' => $posts, 'keywords' => $keywords);
    }
    
    public function searchPostsByWords($all_words = '', $any_words = '') {
        $oposts = new \darkblog\db\posts();
        $okeywords = new \darkblog\db\keywords();
        
        if($all_words == '' && $any_words == '') {
            throw new Exception('At least oe param must not be empty');
        }
        
        $lang_id = $_SESSION['lang'];
        $posts = $oposts->selectByContentMultiple($all_words, $any_words, $lang_id);
        
        $post_ids = array();
        foreach ($posts as $post) {
            $post_ids[] = $post['id'];
        }
        
        if(!empty($post_ids))
            $_keywords = $okeywords->selectByPosts($post_ids, $lang_id);
        else
            $_keywords = array();
        
        //pARSING KEYWORDS
        foreach ($_keywords as $keyword) {
            $keywords[$keyword['id']] = $keyword;
        }

        return array('posts' => $posts, 'keywords' => $keywords);
    }
    
    public function insertPosts($posts) {
        $oposts = new \darkblog\db\posts();
        
        foreach ($posts as $post) {
            //var_dump($post['keywords'], mb_detect_encoding($post['keywords']));echo "<br/><br/><br/><br/><br/><br/>";
            $oposts->insertIgnore($post);
        }
    }
    
    public function insertPostKeywords($post_id, $keyword_id_list) {
        $opk = new \darkblog\db\posts_keywords();
        
        $opk->clear();
        foreach ($keyword_id_list as $keyword_id) {
            $opk->insertIgnore(array('post_id' => $post_id, 'keyword_id' => $keyword_id));
        }
    }
    
    public function blockchanGetPosts() {
        $posts = \darkblog\lib\emercoin::name_filter('^blog:.+', 0, 0, 0, '', 'base64');
        
        $parsed_posts = array();
        $linked_posts = array();
        
        //Parsing ...
        echo "Parsing ...";
        foreach ($posts as $post) {
            $postname = explode(':', $post['name']);
            $postname = array_slice($postname, 1);
            if(count($postname)) {
                $postname = implode(':', $postname);
                
                //Loading big values
                if(strlen($post['value']) > 300) {
                    $_post = \darkblog\lib\emercoin::name_show($post['name'], 'base64');
                    $parsed_posts[$postname] = \darkblog\lib\parser::parse(base64_decode($_post['value']));
                }
                else {
                    $parsed_posts[$postname] = \darkblog\lib\parser::parse(base64_decode($post['value']));
                }
                
            }
        }
        
        //Linking ...
        echo "Linking ...";
        foreach ($parsed_posts as $postname => $post) {
            $linked_posts[$postname] = \darkblog\lib\parser::link($parsed_posts, $post);
        }
        
        return $linked_posts;
    }
    
    
    
    public function clearAll() {
        $oposts = new \darkblog\db\posts();
        $olangs = new \darkblog\db\langs();
        $okeywords = new \darkblog\db\keywords();
        $opk = new \darkblog\db\posts_keywords();
        $okl = new \darkblog\db\keywords_langs();
        $ousers = new \darkblog\db\users();
        $opk->clear();
        $okl->clear();
        $okeywords->clear();
        $oposts->clear();
        $olangs->clear();
        $ousers->clear();
    }
    
    /**
     * 
     * @param type $userkey
     * @param type $username
     * @param type $postsig
     * @param type $post_name
     * @return type
     */
    public function blockchanVerifyPost($userkey, $username, $postsig, $post_name) {
        return \darkblog\lib\emercoin::verifymessage($userkey, $postsig, "$username:$post_name");
    }
    
    public function importPosts() {
        $oposts = new \darkblog\db\posts();
        $olangs = new \darkblog\db\langs();
        $okeywords = new \darkblog\db\keywords();
        $okl = new \darkblog\db\keywords_langs();
        $opk = new \darkblog\db\posts_keywords();
        $ousers = new \darkblog\db\users();
        
        $posts = $this->blockchanGetPosts();
        $insert_posts = array();
        
        foreach ($posts as $postsname => $post) {
            foreach ($post as $k => $v) {
                if($k != 'content') $_post[$k] = $v;
            }
            
            if(isset($post['content'])) {
                //var_dump($post['keywords'], \darkblog\lib\parser::nullempty($post['keywords'])); echo "<br/><br/><br/><br/><br/><br/>";
                $insert_posts[] = array(
                    'lang' => \darkblog\lib\parser::nullempty($post['lang']),
                    'name' => \darkblog\lib\parser::nullempty($postsname),
                    'sig' => \darkblog\lib\parser::nullempty($post['sig']),	
                    'username' => \darkblog\lib\parser::nullempty($post['username']),	
                    'title' => \darkblog\lib\parser::nullempty($post['title']),	
                    'reply' => \darkblog\lib\parser::nullempty($post['reply']),
                    'content' => \darkblog\lib\parser::nullempty($post['content']),
                    'keywords' => \darkblog\lib\parser::nullempty($post['keywords']),
                    'metadata' => serialize($_post)
                );
            }
        }      
        //Inserting primary posts
        $this->insertPosts($insert_posts);
        
        //Inserting other data (from posts) and updating posts
        $posts = $oposts->selectAll(false);

        foreach ($posts as $post) {
            $metadata = unserialize($post['metadata']);
            
            //Lang
            $lang = $post['lang'];
            $lang_id = $post['lang_id'];
            if(empty($lang)) $lang = 'en';
            $olangs->insertIgnore(array('name' => $lang));
            
            if($olangs->affectedRows() == 0) {
                $lang_id = $olangs->getIdByName($lang);
            }
            else {
                $lang_id = $olangs->insertId();
            }
            
            if(empty($post['lang_id']))
                $oposts->updateLang($post['id'], $lang_id);

            //User
            if(!empty($post['username'])) {
                $user = $ousers->getByName($post['username']);
                if(!empty($user)) {
                    $user_id = $user['id'];
                    //var_dump($user['id'], $user['key'], $post['username'], $post['sig'], $post['name']);
                    //var_dump($this->blockchanVerifyPost($user['key'], $post['username'], $post['sig'], $post['name']));
                    if($this->blockchanVerifyPost($user['key'], $post['username'], $post['sig'], $post['name']))
                        $oposts->updateUser($post['id'], $user_id);
                }
            }
            
            //Keywords
            if(!empty($post['keywords'])) {
                $keywords = mb_split(',', $post['keywords']);
                //var_dump($post['keywords'], $keywords);
        
                foreach ($keywords as $keyword) {
                    $keyword = trim($keyword);
                    $okeywords->insertIgnore(array('word' => $keyword));

                    if($okeywords->affectedRows() == 0) {
                        $keyword_id = $okeywords->getIdByKeyword($keyword);
                    }
                    else {
                        $keyword_id = $okeywords->insertId();
                    }
                    $okl->insert_pk($keyword_id, $lang_id);
                    
                    $opk->insert_pk($keyword_id, $post['id']);
                }
            }

            //Reply to other post
            if(!empty($post['reply'])) {
                $reply_id = $oposts->getIdByName($post['reply']);
                if(!empty($reply_id))
                    $oposts->updateReplyPost($post['id'], $reply_id);
            }
            
            //Verified
            $oposts->updateVerified($post['id']);
        }
    }
    
    public function getMyPosts() {
        $posts = \darkblog\lib\emercoin::name_list_filtered("blog:", 'base64');
        
        $posts = array_map(function($post) {
            $post['value'] = base64_decode($post['value']);
            $post['vars'] = \darkblog\lib\parser::parse($post['value']);
            $name = explode(':', $post['name']);
            $post['name'] = $name[1];
            
            $post['expires_in_days'] = ceil($post['expires_in']/175);
            
            return $post;
        }, $posts);
        
        return array_reverse($posts);
    }
    
    public function newPost($name, $content, $vars, $days) {
        $build_output = \darkblog\lib\parser::build($content, $name, $vars);
        //var_dump($build_output); die();
        foreach ($build_output as $index => $value) {
            echo $index;
            if($index == 0) {
                \darkblog\lib\emercoin::name_new('blog:'.$name, $value, $days, '', '');
            }
            else {
                \darkblog\lib\emercoin::name_new('blog:'.$name.'_'.$index, $value, $days, '', '');
            }
        }
    }
    
    public function editPost($name, $content, $vars, $days) {
        $build_output = \darkblog\lib\parser::build($content, $name, $vars);
        
        \darkblog\lib\emercoin::name_update('blog:'.$name, $build_output[0], $days, '', '');
    }
    
    public function deletePost($name) {
        $name = 'blog:'.$name;
        
        \darkblog\lib\emercoin::name_delete($name);
    }
    
    public function getPostData($name) {
        $full_name = 'blog:'.$name;
        
        $post = \darkblog\lib\emercoin::name_show($full_name);
        
        $vars = \darkblog\lib\parser::parse($post['value'], false);
        $post['name'] = $name;
        
        $post['_days'] = $post['expires_in']/175;
        $post['expired'] = ($post['expires_in'] < 0);
        
        if($post['expires_in'] > 0) {
            $post['created'] = date('Y-m-d H:i:s', $post['time']);
            $post['expires'] = date('Y-m-d H:i:s', $post['time'] + round($post['_days']*86400));
        }
        else {
            $post['created'] = date('Y-m-d H:i:s', $post['time']);
            $post['expires'] = date('Y-m-d H:i:s', time() + round($post['_days']*86400));
        }
        
        $post['days'] = ceil($post['expires_in']/175);
        if($post['days'] < 1) $post['days'] = 1;
        
        $post += $vars;
        
        $post['content'] = trim($post['content']);
        //var_dump($post);
        return $post;
    }
    
    /*public function getPostFull($post_id) {
        $oposts = new \darkblog\db\posts();
        $okeywords = new \darkblog\db\keywords();
        $ousers = new \darkblog\db\users();
        
        $post = $oposts->get($post_id);
        $post['_keywords'] = $post['keywords'];
        $lang_id = $_SESSION['lang'];
        $post['keywords'] = $okeywords->selectByPost($post_id, $lang_id);
        if(!empty($post['user_id']))
            $post['user'] = $ousers->get($post['user_id']);
        
        return $post;
    }
    
    public function getPostByNameFull($post_name) {
        $oposts = new \darkblog\db\posts();
        
        $post = $oposts->getByName($post_name);
        
        return $this->getPostFull($post['id']);
    }*/
    
}