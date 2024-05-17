<?php
namespace App\Helpers;

/**
 * Guid generator
 */
class GuidGenerator {
    
    function __construct() {
        
    }
    /**
     * Generate GUID
     * @return string
     */
    public static function generate() {
        if (function_exists('com_create_guid')){
            return com_create_guid();
        } else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
            return $uuid;
        }
    }
    
    /**
    * Determine if supplied string is a valid GUID
    *
    * @param string $guid String to validate
    * @return boolean
    */
   function validationGuid($guid)
   {
       return !empty($guid) && preg_match('/^[A-Za-z0-9]{8}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{12}$/', $guid);
   }
}
