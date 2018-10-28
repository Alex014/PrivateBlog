<?php
namespace darkblog\lib;

/**
 * Operations with files
 *
 * @author user
 */
class files {
    
    public static $buffer = 18432;
    
    public static function split_to_array($filename) {
        //open file to read
        $file_handle = fopen($filename,'r');
        //get file size
        $file_size = filesize($filename);
        //no of parts to split
        $parts = $file_size / self::$buffer;

        //store all the file names
        $file_parts = array();

        //name of input file
        $file_name = basename($filename);

        for($i=0;$i<$parts;$i++){
            //read buffer sized amount from file
            $file_part = fread($file_handle, self::$buffer);
            //add the name of the file to part list
            array_push($file_parts, $file_part);
        }    
        //close the main file handle

        fclose($file_handle);
        return $file_parts;
    }
    
    public static function split_to_files($filename, $partname = '') {
        //open file to read
        $file_handle = fopen($filename,'r');
        //get file size
        $file_size = filesize($filename);
        //no of parts to split
        $parts = $file_size / self::$buffer;

        //store all the file names
        $file_parts = array();

        //name of input file
        $file_name = basename($filename);

        for($i=0;$i<$parts;$i++){
            //read buffer sized amount from file
            $file_part = fread($file_handle, self::$buffer);
            //the filename of the part
            $file_part_path = $file_name.".$partname$i";
            //open the new file [create it] to write
            $file_new = fopen($file_part_path,'w+');
            //write the part of file
            fwrite($file_new, $file_part);
            //add the name of the file to part list [optional]
            array_push($file_parts, $file_part_path);
            //close the part file handle
            fclose($file_new);
        }    
        //close the main file handle

        fclose($file_handle);
        return $file_parts;
    }
}
