<?php
namespace darkblog\objects;

/**
 * Description of files
 *
 * @author user
 */
class files {
    
    public function getMyFiles() {
        $files = \darkblog\lib\emercoin::name_list_filtered("file:", 'base64');
        
        return array_map(function($file) {
            $name = explode(':', $file['name']);
            $file['name'] = '';
            for($i == 0; $i < count($name); $i++) {
                if($i > 1)
                    $file['name'] .= ':'.$name[$i];
                elseif($i > 0)
                    $file['name'] .= $name[$i];
            }
            
            return $file;
        }, $files);
    }
    
    public function newFile($name, $value, $days) {
        $name = 'file:'.$name;
        
        \darkblog\lib\emercoin::name_new($name, base64_encode($value), $days, '', 'base64');
    }
    
    public function newFiles($files, $days) {
        $values = array();
        foreach ($files as $filename => $value) {
            $values['file:'.$filename] = base64_encode($value);
        }
        
        unset($files);
        
        \darkblog\lib\emercoin::names_new($values, $days, '', 'base64');
    }
    
    public function editFile($name, $value, $days) {
        $name = 'file:'.$name;
        
        \darkblog\lib\emercoin::name_update($name, $value, $days);
    }
    
    public function deleteFile($name) {
        $name = 'file:'.$name;
        
        \darkblog\lib\emercoin::name_delete($name);
    }
    
    public function getFileData($name) {
        $full_name = 'file:'.$name;
        //var_dump($name);
        $dt = \darkblog\lib\emercoin::name_show($full_name, 'base64');
        //var_dump($dt);
        $data = array();
        $data['content'] = base64_decode($dt['value']);
        //var_dump($data);
        $data['name'] = $name;
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