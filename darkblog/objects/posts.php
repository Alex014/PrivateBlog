<?php
namespace darkblog\objects;

/**
 * Description of posts
 *
 * @author user
 */
class posts {

    private $lang_id = 0;
    
    public function __construct() {
        $olang = new \darkblog\db\langs();
        if(isset($_SESSION) && isset($_SESSION['lang'])) {
            $this->lang_id = $olang->getIdByName($_SESSION['lang']);
        }
    }
    
    public function getPost($post_id) {
        $oposts = new \darkblog\db\posts();
        $okeywords = new \darkblog\db\keywords();
        $ousers = new \darkblog\db\users();
        
        $post = $oposts->get($post_id);
        $post['_keywords'] = $post['keywords'];
        $lang_id = $this->lang_id;
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
        $lang_id = $this->lang_id;
        
        if(!empty($post['id']))
            $post['keywords'] = $okeywords->selectByPost($post['id'], $lang_id);

        if(!empty($post['user_id']))
            $post['user'] = $ousers->get($post['user_id']);
        
        return $post;
    }
    
    public function getByKeyword($keyword_id) {
        $oposts = new \darkblog\db\posts();
        $lang_id = $this->lang_id;
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
        
        $lang_id = $this->lang_id;
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

        $keywords = array_filter($keywords, function ($row) {
            if (false === strpos($row['word'], '@') && false === strpos($row['word'], '\\') && false === strpos($row['word'], '"') && false === strpos($row['word'], '\'')) {
                return $row;
            } else {
                return false;
            }
        });
        
        return array('posts' => $posts, 'keywords' => $keywords);
    }
    
    public function searchPostsByContent($regexp) {
        $oposts = new \darkblog\db\posts();
        $okeywords = new \darkblog\db\keywords();
        
        $lang_id = $this->lang_id;
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

        $keywords = array_filter($keywords, function ($row) {
            if (false === strpos($row['word'], '@') && false === strpos($row['word'], '\\') && false === strpos($row['word'], '"') && false === strpos($row['word'], '\'')) {
                return $row;
            } else {
                return false;
            }
        });
        
        return array('posts' => $posts, 'keywords' => $keywords);
    }
    
    public function searchPostsByWords($all_words = '', $any_words = '') {
        $oposts = new \darkblog\db\posts();
        $okeywords = new \darkblog\db\keywords();
        
        if($all_words == '' && $any_words == '') {
            throw new \Exception('At least oe param must not be empty');
        }
        
        $lang_id = $this->lang_id;
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

        $keywords = array_filter($keywords, function ($row) {
            if (false === strpos($row['word'], '@') && false === strpos($row['word'], '\\') && false === strpos($row['word'], '"') && false === strpos($row['word'], '\'')) {
                return $row;
            } else {
                return false;
            }
        });

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
        echo "Parsing ...<br>";
        foreach ($posts as $post) {
            $postname = explode(':', $post['name']);
            $postname = array_slice($postname, 1);
            if(count($postname)) {
                $postname = implode(':', $postname);
                
                //Loading big values
                // if(strlen($post['value']) > 300) {
                    $_post = \darkblog\lib\emercoin::name_show($post['name'], 'base64');
                    $parsed_posts[$postname] = \darkblog\lib\parser::parse(base64_decode($_post['value']));
                // }
                // else {
                //     $parsed_posts[$postname] = \darkblog\lib\parser::parse(base64_decode($post['value']));
                // }
                
            }
        }
        
        //Linking ...
        echo "Linking ...<br><br><br>";
        foreach ($parsed_posts as $postname => $post) {
            //echo "$postname ... ";
            $linked_posts[$postname] = \darkblog\lib\parser::link($parsed_posts, $post);
            //echo "OK<br>";
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
        try {
            return \darkblog\lib\emercoin::verifymessage($userkey, $postsig, "$username:$post_name");
        } catch (\Throwable $exc) {
            if (false !== strpos($exc->getMessage(), 'Invalid address')) {
                return False;
            }
        }
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
        echo " <br><br> blockchanGetPosts() - OK <br><br> "; 
        foreach ($posts as $postsname => $post) {
            foreach ($post as $k => $v) {
                if($k != 'content') $_post[$k] = $v;
            }
            
            if(isset($post['content'])) {
                // var_dump($post['keywords'], \darkblog\lib\parser::null_empty('keywords', $post)); echo "<br/><br/><br/><br/><br/><br/>";
                $insert_posts[] = array(
                    'lang' => \darkblog\lib\parser::null_empty('lang', $post),
                    'name' => \darkblog\lib\parser::nullempty($postsname),
                    'sig' => \darkblog\lib\parser::null_empty('sig', $post),	
                    'username' => \darkblog\lib\parser::null_empty('username', $post),	
                    'title' => \darkblog\lib\parser::null_empty('title', $post),	
                    'reply' => \darkblog\lib\parser::null_empty('reply', $post),
                    'content' => \darkblog\lib\parser::null_empty('content', $post),
                    'keywords' => \darkblog\lib\parser::null_empty('keywords', $post),
                    'metadata' => serialize($_post)
                );
            }
        }      
        //Inserting primary posts
        $this->insertPosts($insert_posts);
        
        echo " <br><br> insertPosts() - OK <br><br> "; 
        echo " <br><br> Updating posts ... <br><br> "; 
        //Inserting other data (from posts) and updating posts
        $posts = $oposts->selectAll(false);

        foreach ($posts as $post) {
            $update = array();
            $metadata = unserialize($post['metadata']);
            //echo $post['name'].": ";
            //Lang
            $lang = $post['lang'];
            if(empty($lang)) $lang = 'en';
            
            $lang_id = $olangs->getIdByName($lang);
            
            if(empty($lang_id)) {
                $olangs->insert(array('name' => $lang));
                $lang_id = $olangs->insertId();
            }
            
            $update['lang_id'] = $lang_id;
            //if(empty($post['lang_id']))
                //$oposts->updateLang($post['id'], $lang_id);
            //echo " lang-ok ";
            //User
            if(!empty($post['username'])) {
                $user = $ousers->getByName($post['username']);
                if(!empty($user)) {
                    $user_id = $user['id'];
                    //var_dump($user['id'], $user['key'], $post['username'], $post['sig'], $post['name']);
                    //var_dump($this->blockchanVerifyPost($user['key'], $post['username'], $post['sig'], $post['name']));
                    if($this->blockchanVerifyPost($user['key'], $post['username'], $post['sig'], $post['name'])) {
                        $update['user_id'] = $user_id;
                        //$oposts->updateUser($post['id'], $user_id);
                    }
                }
            }
            //echo " blogger-ok ";
            
            //Keywords
            if(!empty($post['keywords'])) {
                $keywords = mb_split(',', $post['keywords']);
                //var_dump($post['keywords'], $keywords);
        
                foreach ($keywords as $keyword) {
                    $keyword = trim($keyword);
            
                    $keyword_id = $okeywords->getIdByKeyword($keyword);

                    if(empty($keyword_id)) {
                        $okeywords->insert(array('word' => $keyword));
                        $keyword_id = $okeywords->insertId();
                    }

                    $okl->insert_pk($keyword_id, $lang_id);
                    
                    $opk->insert_pk($keyword_id, $post['id']);
                }
            }
            //echo " Keywords-ok ";

            //Reply to other post
            if(!empty($post['reply'])) {
                $reply_id = $oposts->getIdByName($post['reply']);
                if(!empty($reply_id))
                    $update['reply_id'] = $reply_id;
                    //$oposts->updateReplyPost($post['id'], $reply_id);
            }
            //echo " reply-ok ";
            
            //Verified
            //$oposts->updateVerified($post['id']);
            $update['v'] = 1;
            $oposts->update($post['id'], $update);
            
            //echo " ... OK<br><br> ";
        }
        
        
        echo " <br><br> updatePosts() - OK <br><br> "; 
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

        if(count($build_output) == 1) {
            \darkblog\lib\emercoin::name_new('blog:'.$name, $build_output[0], $days, '', '');
        }
        elseif(count($build_output) == 2) {
            \darkblog\lib\emercoin::name_new('blog:'.$name, $build_output[1], $days, '', '');
        }
        else {
            foreach ($build_output as $index => $value) {
                if($index == 0) {
                    \darkblog\lib\emercoin::name_new('blog:'.$name, $value, $days, '', '');
                }
                else {
                    \darkblog\lib\emercoin::name_new('blog:'.$name.'_'.$index, $value, $days, '', '');
                }
            }
        }
    }
    
    public function editPost($name, $content, $vars, $days) {
        //var_dump($content);
        $build_output = \darkblog\lib\parser::build($content, $name, $vars);

        if(count($build_output) == 1) {
            \darkblog\lib\emercoin::name_update('blog:'.$name, $build_output[0], $days, '', '');
        }
        elseif(count($build_output) == 2) {
            \darkblog\lib\emercoin::name_update('blog:'.$name, $build_output[1], $days, '', '');
        }
        else {
            foreach ($build_output as $index => $value) {
                if($index == 0) {
                    \darkblog\lib\emercoin::name_update('blog:'.$name, $value, $days, '', '');
                }
                else {
                    \darkblog\lib\emercoin::name_new('blog:'.$name.'_'.$index, $value, $days, '', '');
                }
            }
        }
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
        $lang_id = $this->lang_id;
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