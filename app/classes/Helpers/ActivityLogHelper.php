<?php
namespace App\Helpers;

use App\Databases\DbConnectionProvider;

/**
 * Description of ActivityLogHelper
 *
 * @author b.pelko
 */
class ActivityLogHelper {

    /**
     * @var DbConnectionProvider
     */
    private $dbConn;
    private $logger;
    private $table = "activitylog";

    function __construct(DbConnectionProvider $dbConn) {        
        $this->dbConn = $dbConn;
        $this->logger = $dbConn->getLogger();
    }
    
    function getTableName() {
        return $this->dbConn->getTablePrefix().$this->table;
    }
    
    public function saveChange($msg, $inputdata, $entityid="", $parent_entity_id = "", $entityname = "", $oldstate = 0, $newState=0) {
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
            
            $this->dbConn->insert($this->getTableName(), $insert);
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
}
