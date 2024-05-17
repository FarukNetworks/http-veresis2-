<?php
namespace App\Models;

use App\Helpers\GuidGenerator;

/**
 * Description of BaseModel
 *
 * @author b.pelko
 */
class BaseModel {
    
    public $id;
    public $created_at;
    public $created_by;    
    public $updated_at;
    public $updated_by;
    
    public function __construct() {
        $a = func_get_args(); 
        $i = func_num_args(); 
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array(array($this,$f),$a); 
        } 
    }
          
    public function __construct1($id) {
        $this->id = $id;
    }
    
    /**
     * Set primary key on table
     */
    public function setId() {
        $this->id = GuidGenerator::generate();
    }
    
    function getId() {
        return $this->id;
    }

        
    /**
     * Set created info
     * @param (optional) string $who - who is created record
     */
    public function setCreatedInfo($who = '') {
        if (!empty($who)) {
            $this->created_by = $who;
        } else {
            $this->created_by = '';
        }
        $this->created_at = date("Y-m-d H:i:s");
    }
    
    /**
     * Set updated info
     * @param (optional) string $who - who is update record
     */    
    public function setUpdatedInfo($who = '') {
        if (!empty($who)) {
            $this->updated_by = $who;
        }
        $this->updated_at = date("Y-m-d H:i:s");
    }
}
