<?php
/**
 * Application class loader
 * 
 * Used in autoload.php
 * @author b.pelko
 */
namespace App\Infrastructure;

class ClassLoader {
    
    /**
     * Load required application files
     * @param type $path
     * @return type
     */
    public static function loadFiles($path) {
        $arrayfiles = self::listDir($path);
        //print_r($arrayfiles);
        
        if (empty($arrayfiles)) return;
        foreach ($arrayfiles as $filePath) {
            require $filePath;
//echo $filePath." OK. \n\n";
        }
    }
    
    /**
     * Read files in directory - use recursicion
     * @param type $start_dir
     * @return boolean
     */
    private static function listDir($start_dir='.') 
    {
        $files = array();
        if (is_dir($start_dir)) {
            $fh = opendir($start_dir);
            while (($file = readdir($fh)) !== false) {
                # loop through the files, skipping . and .., and recursing if necessary
                if (strcmp($file, '.')==0 || strcmp($file, '..')==0) continue;
                $filepath = $start_dir . '/' . $file;
                if ( is_dir($filepath) )
                    $files = array_merge($files, listdir($filepath));
                else
                    array_push($files, $filepath);
            }
            closedir($fh);
        } else {
            # false if the function was called with an invalid non-directory argument
            $files = false;
        }
        return $files;        
    }
}