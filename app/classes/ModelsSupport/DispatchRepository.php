<?php
namespace App\ModelsSupport;

use App\Databases\DbConnectionProvider;
use App\SearchModels\DispatchSearchModel;
use \PDO;


class DispatchRepository {

	private $dbConn;
	private $logger;

	function __construct(DbConnectionProvider $dbConn) {
        $this->dbConn = $dbConn;
        $this->logger = $dbConn->getLogger();
    }

    function getDispatches(DispatchSearchModel $model, $customers) 
    {
    	try
        {
            //var_dump($model);

            $select = "SELECT *, CAST(DispatchByPost AS unsigned integer) as DispatchByPost FROM crm_dispatch WHERE 1=1";
            
            $filters = array();
            if(!empty($model->customerId)) {
                $select .= " AND CustomerId = ? ";
                $filters[] = $model->customerId;
            } else {
                $tmpCustomerIds = array_map(function ($item) {
                    return $item['Id'];
                }, $customers['customers']);
                
                $inString = implode(',', array_fill(0, count($tmpCustomerIds), '?'));

                $select .= " AND CustomerId IN ($inString) ";
                $filters = array_merge($filters, $tmpCustomerIds);
            }

            $sort = $model->getSortSql();
            $sql = $select.$sort["sortsql"];
            //var_dump($filters);
            //echo $sql;
            $allrecords = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
                   
            $paging = $model->getSql();
            $sql = $select. $paging["sortsql"].$paging["pagingsql"];
            $filters = array_merge($filters, $paging["pagingparams"]);
            
            //echo $sql;
            $dispatches = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);     
            
            $data = [
                'TotalRows' => count($allrecords),
                'ListDispatches' => $dispatches
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

    function getDispatchesForOrder($dispatchIds) {
        if (empty($dispatchIds)) return array();

        try
        {
            $inString = '"'.implode('","',$dispatchIds).'"';

            $sql = "SELECT *, CAST(DispatchByPost AS unsigned integer) as DispatchByPost 
                    FROM crm_dispatch where id in ($inString)";
            //echo $sql;
            $dispatches = $this->dbConn->fetch_custom($sql, array(), \PDO::FETCH_ASSOC);
            
            return $dispatches;
            
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

    function getDispatchDetail($dispatchId) 
    {
    	try
        {
            $filters = array('id' => $dispatchId);

            $sql = "SELECT * FROM crm_dispatch d
                    LEFT JOIN crm_dispatchdetail dd ON d.Id = dd.DispatchId 
                    WHERE d.Id = ?";

            //echo $sql;
            $dispatches = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
            
            return $dispatches;

            
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

    function allowShowDispatch($orderid, $userCustomerId, $allowedCustomerIds) {
        try
        {
            $filters = array('id' => $orderid, 'customerId' => $userCustomerId);


            $sql = "SELECT * FROM crm_dispatch d
                    WHERE d.Id = ? and d.customerId = ? ";

            if(!empty($allowedCustomerIds)) {
                foreach($allowedCustomerIds as $id){    
                    $sql .= "or d.customerId = ? ";
                

                array_push($filters, $id);

                }
            }

            // echo $sql;
            $dispatches = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
            if (count($dispatches) > 0)
                return true;
            return false;
            
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

    function getDispatchCustomerId($dispatchid) {

        try
        {
            $filters = array('dispatchId' => $dispatchid);

            $sql = "SELECT d.customerId FROM crm_dispatch d
                    WHERE d.Id = ?";

            // echo $sql;
            $customerId = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);

            return $customerId[0]['customerId'];
            
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