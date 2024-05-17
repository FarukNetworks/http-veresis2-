<?php
namespace App\ModelsSupport;

use App\Databases\DbConnectionProvider;
/**
 * Description of UserRepository
 *
 * @author b.pelko
 */
class UserRepository {

    /**
     * @var DbConnectionProvider
     */
    private $dbConn;

    function __construct(DbConnectionProvider $dbConn) {
        
        $this->dbConn = $dbConn;
        $this->logger = $dbConn->getLogger();
    }

    public function getUserById($user_id) {
        try
        {
            $sql = 'select CAST(canViewSubunits AS unsigned integer) as canViewSubUnits1,
                        CAST(canViewOrders AS unsigned integer) as canViewOrders1, 
                        CAST(canViewAllDestroyedPlates AS unsigned integer) as canViewAllDestroyedPlates1,
                        CAST(canViewAllFakeDestroyedPlates AS unsigned integer) as canViewAllFakeDestroyedPlates1,
                        customerId
                    from crm_user where id=?';

            $user = $this->dbConn->fetch_custom($sql, array($user_id));
            
            $canViewSubUnits = ($user[0]->canViewSubUnits1 == 1) ? true : false;
            $canViewOrders = ($user[0]->canViewOrders1 == 1) ? 'orders' : 'all';
            $canViewAllDestroyedPlates = ($user[0]->canViewAllDestroyedPlates1 == 1) ? true : false;
            $canViewAllFakeDestroyedPlates = ($user[0]->canViewAllFakeDestroyedPlates1 == 1) ? true : false;
            
            $data = [
                'canViewSubUnits' => $canViewSubUnits,
                'canViewOrders' => $canViewOrders,
                'canViewAllDestroyedPlates' => $canViewAllDestroyedPlates,
                'canViewAllFakeDestroyedPlates' => $canViewAllFakeDestroyedPlates,
                'customerId' => $user[0]->customerId    
            ];

            return $data;
                        
        }
        catch(\PDOException $pdoex)
        {
            $this->logger->error($pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        }
        catch(\Exception $ex)
        {
            $this->logger->error($ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }

    public function getUserReferences($params) {

        // echo $updated_at;

        try
        {
            $username = $params['username'];
            $pass_key = $params['pass_key'];
            // $updated_at = $params['updated_at'];
            $sql = 'SELECT * FROM crm_user 
                    WHERE username = ? and pass_key = ? and updated_at >= NOW() - INTERVAL 1 DAY';
            // echo $sql;
            $user = $this->dbConn->fetch_custom($sql, array($username, $pass_key));

            return $user;
                        
        }
        catch(\PDOException $pdoex)
        {
            $this->logger->error($pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        }
        catch(\Exception $ex)
        {
            $this->logger->error($ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }

    public function getAllowedCustomersId($customer_id) {
        try
        {
            $sql = 'select Id from crm_customer where parentId = ?';

            $allowedIds = $this->dbConn->fetch_custom($sql, array($customer_id));

            $ids = array();

            if(!empty($allowedIds)) {
                foreach($allowedIds as $id) {
                    array_push($ids, $id->Id);
                }
            }
            
            return $ids;
                        
        }
        catch(\PDOException $pdoex)
        {
            $this->logger->error($pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        }
        catch(\Exception $ex)
        {
            $this->logger->error($ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }
}
