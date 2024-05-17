<?php
namespace App\ModelsSupport;

use App\Databases\DbConnectionProvider;
use App\SearchModels\BoxSearchModel;
use \PDO;


class BoxRepository {

	private $dbConn;
	private $logger;

	function __construct(DbConnectionProvider $dbConn) {
        $this->dbConn = $dbConn;
        $this->logger = $dbConn->getLogger();
    }

    function getBoxes(BoxSearchModel $model, $customers) 
    {
    	try
        {
            //var_dump($model);

            $select = "SELECT *, CAST(IsCustom AS unsigned integer) as IsCustom, CAST(DispatchByPost AS unsigned integer) as DispatchByPost FROM crm_box where 1=1";
            
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
            $boxes = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
            
            $data = [
                'TotalRows' => count($allrecords),
                'ListBoxes' => $boxes
            ];

            return $data;
        }
        catch(\PDOException $pdoex)
        {
            $this->logger->error($pdoex->getTraceAsString());
            throw new \Exception('Dostop zavrnjen!');
            // throw new \PDOException($pdoex);
        }
        catch(\Exception $ex)
        {
            $this->logger->error($ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }

    function getBoxDetail($boxId) 
    {
        try
        {
            $filters = array('id' => $boxId);

            $sql = "SELECT *, CAST(IsCustom AS unsigned integer) as IsCustom, CAST(DispatchByPost AS unsigned integer) as DispatchByPost FROM crm_box d
                    LEFT JOIN crm_boxdetail dd ON d.Id = dd.boxId 
                    WHERE d.Id = ?";

            //echo $sql;
            $boxes = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
            
            return $boxes;

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

    function allowShowBox($boxid, $userCustomerId, $allowedCustomerIds) {
        try
        {
            $filters = array('id' => $boxid, 'customerId' => $userCustomerId);


            $sql = "SELECT * FROM crm_box d
                    WHERE d.Id = ? and d.CustomerId = ? ";

            if(!empty($allowedCustomerIds)) {
                foreach($allowedCustomerIds as $id){    
                    $sql .= "or d.customerId = ? ";
                

                array_push($filters, $id);

                }
            }


            // echo $sql;
            $boxes = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
            if (count($boxes) > 0)
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

    function getBoxCustomerId($boxid) {

        try
        {
            $filters = array('boxId' => $boxid);

            $sql = "SELECT d.customerId FROM crm_box d
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