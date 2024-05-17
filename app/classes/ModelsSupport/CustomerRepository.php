<?php
namespace App\ModelsSupport;

use App\Databases\DbConnectionProvider;
use \PDO;


class CustomerRepository {

	private $dbConn;
	private $logger;

	function __construct(DbConnectionProvider $dbConn) {
        $this->dbConn = $dbConn;
        $this->logger = $dbConn->getLogger();
    }

    function getCustomers($userid) 
    {
    	try
        {
            //todo: get all customers for user
            //$user = $this->dbConn->fetchSingleRow('crm_user', 'id', $userid);
            $user = $this->dbConn->fetch_custom('select *, CAST(canViewSubunits AS unsigned integer) as canViewSubUnits1 from crm_user where id=?', array($userid));
            
            //var_dump($user[0]);

            $customerId = $user[0]->customerId;
            $canViewSubUnits = ($user[0]->canViewSubUnits1 == 1) ? true : false;
             
            $conditional = "and (Id = '{$customerId}')"; 
            if ($canViewSubUnits) {
                $conditional = "and (Id = '{$customerId}' or ParentId='{$customerId}')";
            }        

            $sql = "select Id, Name from crm_customer where IsActive=1 {$conditional} order by name";
            //echo $sql;            

            $customers = $this->dbConn->fetch_custom($sql, array(), \PDO::FETCH_ASSOC);

            $tmp = [
                'customers' => $customers,
                'canViewSubUnits' => $canViewSubUnits,
                'customerUserId' => $userid,
                'userCustomerId' => $customerId
            ];

            return $tmp;
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