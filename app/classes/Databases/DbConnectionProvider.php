<?php 
/**
 * DbConnectionProvider - handler for database connection
 
 * @author Boštjan Pelko Sibit d.o.o.
 */
namespace App\Databases;

use PDO;

class DbConnectionProvider extends \PDO
{

    private $dbSettings;

    /**
     * Logger
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     * Db connection pointer
     * @var type PDO
     */
    private $db;
    
    /**
     * Db connection provider constructor
     * @param array $dbSettings
     * @param type $logger
     * @throws \Exception
     */
    public function __construct($dbSettings, $logger) {
        
        $this->logger = $logger;
        try 
        {
            if (empty($dbSettings)) {
                throw new \Exception("Db settings is not provided");
            }
            $this->dbSettings = $dbSettings;
                        
            $server = "mysql:host=".$dbSettings['host'].";dbname=".$dbSettings['database'];
                                
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");
            $this->db = new PDO($server, $dbSettings["username"], $dbSettings["password"], $options);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);            
            $this->db->exec("SET CHARACTER SET utf8");
            $this->db->exec("SET NAMES utf8 COLLATE utf8_slovenian_ci");
                        
        } 
        catch (\PDOException $pdoex) {
            $this->logger->emergency($pdoex);
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        } 
        catch (\Exception $ex) {
            $this->logger->emergency($ex);
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }       
    }
        
    /**
     * Open database connection
     *  
     * @return PDO
     * @throws \PDOException
     * @throws \Exception
     */
    public function openConnection() 
    {
        try 
        {
            return $this->db;
        } 
        catch (\PDOException $pdoex) {
            $this->logger->emergency($pdoex);
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        } 
        catch (\Exception $ex) {
            $this->logger->emergency($ex);
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }   
    	    
    /**
     * Close database connection
     * @throws \PDOException
     * @throws \Exception
     */
    public function closeConnection()
    {
        try
        {
           $this->db = null;           
        }
        catch (\PDOException $pdoex) {
            $this->logger->emergency($pdoex);
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        } 
        catch (\Exception $ex) {
            $this->logger->emergency($ex);
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }        
    }
    
    public function getLogger() {
        return $this->logger;
    }
    
    /**
     * Get table prefix if define in settings
     * @return string
     */
    public function getTablePrefix() {
        return $this->dbSettings["table_prefix"];
    }

    public function fetch_custom( $sql,$data=null, $fetchType = PDO::FETCH_OBJ) {
        if ($data!==null) {
        $dat=array_values($data);
        }
        $sel = $this->db->prepare( $sql );
        if ($data!==null) {
            $sel->execute($dat);
        } else {
            $sel->execute();
        }
        $sel->setFetchMode( $fetchType );
        $obj = $sel->fetchAll();
        return $obj;
        
    }
    /**
     * Activity chenge log
     * @param type $msg
     * @param type $inputdata
     * @param type $entityid
     * @param type $parent_entity_id
     * @param type $entityname
     * @param type $oldstate
     * @param type $newState
     * @throws \PDOException
     * @throws \Exception
     */
    public function saveActivityChangeLog($msg, $inputdata, $entityid="", $parent_entity_id = "", $entityname = "", $oldstate = 0, $newState=0) {
        try
        {
            $insert = [
                'description' => $msg,
                'input_data' => $inputdata,
                'entity_id' => $entityid,
                'parent_entity_id' => $parent_entity_id,
                'entity_name' => $entityname,
                'old_state' => $oldstate,
                'new_state' => $newState
            ];
            
            $this->insert($this->getTablePrefix().'activitylog', $insert);
        }
        catch (\PDOException $pdoex) {
            $this->logger->addError($pdoex->getMessage()."\n".$pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        } 
        catch (\Exception $ex) {
            $this->logger->addError($ex->getMessage()."\n".$ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }
    
    /**
    * insert data to table
    * @param  string $table table name
    * @param  array $dat   associative array 'column_name'=>'val'
    */
    public function insert($table,$dat) {
        try 
        {
            if ($dat !== null) {
                $data = array_values($dat);
            }
            //grab keys 
            $cols=array_keys($dat);
            $col=implode(', ', $cols);
            //grab values and change it value
            $mark=array();
            foreach ($data as $key) {
              $keys='?';
              $mark[]=$keys;
            }
            $im=implode(', ', $mark);
            $ins = $this->db->prepare("INSERT INTO $table ($col) values ($im)");
            $ins->execute( $data );
            
        } 
        catch (\PDOException $pdoex) {
            $this->logger->addError($pdoex->getMessage()."\n".$pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        } 
        catch (\Exception $ex) {
            $this->logger->addError($pdoex->getMessage()."\n".$pdoex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }      
        
    }
    /**
    * update record
    * @param  string $table table name
    * @param  array $dat   associative array 'col'=>'val'
    * @param  string $id    primary key column name
    * @param  int $val   key value
    */
    public function update($table,$dat,$id,$val) {
        if ($dat !== null) {
            $data = array_values($dat);
        }
        array_push($data,$val);
        //grab keys
        $cols=array_keys($dat);
        $mark=array();
        foreach ($cols as $col) {
        $mark[]=$col."=?"; 
        }
        $im=implode(', ', $mark);
        $ins = $this->db->prepare("UPDATE $table SET $im where $id=?");
        $ins->execute( $data );
    }
    /**
    * delete record
    * @param  string $table table name
    * @param  string $where column name for condition (commonly primay key column name)
    * @param   int $id   key value
    */
    public function delete( $table, $where,$id ) { 
        $data = array( $id ); 
        $sel = $this->db->prepare("Delete from $table where $where=?" );
        $sel->execute( $data );
    }
    
    /**
    * delete record
    * @param  string $table table name
    * @param  string $where column name for condition (commonly primay key column name)
    * @param   int $id   key value
    */
    public function deleteAll($table) { 
        try
        {
             $sel = $this->db->prepare("Delete from $table");
             $sel->execute();
        }
        catch (\PDOException $pdoex) {
            $this->logger->addError($pdoex->getMessage()."\n".$pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        } 
        catch (\Exception $ex) {
            $this->logger->addError($pdoex->getMessage()."\n".$pdoex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }       
    }
    
    /**
    * fetch only one row 
    * @param  string $table table name
    * @param  string $col condition column
    * @param  string $val value column
    * @return array recordset
    */
    public function fetchSingleRow($table,$col,$val)     
    {       
        $nilai=array($val);
        $sel = $this->db->prepare("SELECT * FROM $table WHERE $col=?");
        $sel->execute($nilai);
        $sel->setFetchMode( PDO::FETCH_OBJ );
        $obj = $sel->fetch();
        return $obj;
    }
    /**
    * fetch all data 
    * @param  string $table table name
    * @return array recordset
    */
    public function fetchAll($table)
    {
        $sel = $this->db->prepare("SELECT * FROM $table");
        $sel->execute();
        $sel->setFetchMode( PDO::FETCH_OBJ );        
        $obj = $sel->fetchAll();
        return $obj;
    }
    /**
    * fetch multiple row
    * @param  string $table table name
    * @param  array $dat specific column selection
    * @return array recordset
    */
    public function fetchCol($table,$dat)
    {
        if ($dat !== null) {
            $cols = array_values($dat);
        }
        $col=implode(', ', $cols);
        $sel = $this->db->prepare("SELECT $col from $table");
        $sel->execute();
        $sel->setFetchMode( PDO::FETCH_OBJ );
        return $sel;
    }
    /**
    * fetch row with condition
    * @param  string $table table name
    * @param  array $col which columns name would be select 
    * @param  array $where what column will be the condition
    * @return array recordset
    */
    public function fetchMultRow($table,$col,$where)
    {
        $data = array_values( $where ); 
        //grab keys 
        $cols=array_keys($where);
        $colum=implode(', ', $col);
        foreach ($cols as $key) {
          $keys=$key."=?";
          $mark[]=$keys;
        }
        $jum=count($where);
        if ($jum>1) {
            $im=implode(' and  ', $mark);
            //echo "SELECT $colum from $table WHERE $im";
            
             $sel = $this->db->prepare("SELECT $colum from $table WHERE $im");
        } else {
          $im=implode('', $mark);
            //echo "SELECT $colum from $table WHERE $im"; 
            $sel = $this->db->prepare("SELECT $colum from $table WHERE $im");
        }
        $sel->execute( $data );
        $sel->setFetchMode( PDO::FETCH_OBJ );
        $obj = $sel->fetchAll();
        return  $obj;
    }
    /**
    * check if there is exist data
    * @param  string $table table name 
    * @param  array $dat array list of data to find
    * @return true or false
    */
    public function checkExists($table,$dat) {
        $data = array_values( $dat ); 
       //grab keys 
        $cols=array_keys($dat);
        $col=implode(', ', $cols);
        foreach ($cols as $key) {
          $keys=$key."=?";
          $mark[]=$keys;
        }
        $jum=count($dat);
        if ($jum>1) {
            $im=implode(' and  ', $mark);
             $sel = $this->db->prepare("SELECT $col from $table WHERE $im");
        } else {
          $im=implode('', $mark);
             $sel = $this->db->prepare("SELECT $col from $table WHERE $im");
        }
        //var_dump($sel);
        $sel->execute( $data );
        $sel->setFetchMode( PDO::FETCH_OBJ );
        $jum=$sel->rowCount();
        if ($jum>0) {
            return true;
        } else {
            return false;
        }     
    }
    /**
    * search data
    * @param  string $table table name
    * @param  array $col   column name
    * @param  array $where where condition 
    * @return array recordset
    */
    public function search($table,$col,$where) {
        $data = array_values( $where );
        foreach ($data as $key) {
           $val = '%'.$key.'%';
           $value[]=$val;
        }
       //grab keys 
        $cols=array_keys($where);
        $colum=implode(', ', $col);
        foreach ($cols as $key) {
          $keys=$key." LIKE ?";
          $mark[]=$keys;
        }
        $jum=count($where);
        if ($jum>1) {
            $im=implode(' OR  ', $mark);
             $sel = $this->db->prepare("SELECT $colum from $table WHERE $im");
        } else {
          $im=implode('', $mark);
             $sel = $this->db->prepare("SELECT $colum from $table WHERE $im");
        }
           
        $sel->execute($value);
        $sel->setFetchMode( PDO::FETCH_OBJ );
        return  $sel;
    }

    function count($table) {
        $sel = $this->db->prepare("SELECT COUNT(*) as cnt from $table");
        $sel->execute();
        $sel->setFetchMode( PDO::FETCH_OBJ );
        $obj = $sel->fetch();
        return  $obj->cnt;
    }    
}