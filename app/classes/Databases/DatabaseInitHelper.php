<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Databases;

use App\Helpers\Uri;

/**
 * Description of DatabaseInitHelper
 *
 * @author b.pelko
 */
class DatabaseInitHelper {

    /**
     * @var DbConnectionProvider
     */
    private $dbConn;

    public function __construct(DbConnectionProvider $dbConn) {
        
        $this->dbConn = $dbConn;
    }
    
    public function Update() {
        $this->dbConn->openConnection();
        
        $tobeupdate = $this->dbConn->fetchAll('tsa_productgroup');
        //var_dump($tobeupdate);
        foreach($tobeupdate as $obj) {
            
            $dat = ['slug' => Uri::generateUrlSlug($obj->description)];
            $this->dbConn->update('tsa_productgroup', $dat, 'id', $obj->id);
        }
        
        //$data = array('slug' => Uri::generateUrlSlug($str))
        //$this->dbConn->update('tsa_productgroup', , 'Id', $val)
    }
    
    
}
