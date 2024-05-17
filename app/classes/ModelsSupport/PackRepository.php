<?php
namespace App\ModelsSupport;

use App\Databases\DbConnectionProvider;
use App\SearchModels\PackSearchModel;
use \PDO;


class PackRepository {

	private $dbConn;
	private $logger;

	function __construct(DbConnectionProvider $dbConn) {
        $this->dbConn = $dbConn;
        $this->logger = $dbConn->getLogger();
    }

    function getPacks(PackSearchModel $model, $customers) 
    {
    	try
        {
            //var_dump($model);

            $select = "SELECT * FROM crm_box";
            
            $filters = array();
            if(!empty($model->customerId)) {
                $select .= " where CustomerId = ? ";
                $filters[] = $model->customerId;
            } else {
                $tmpCustomerIds = array_map(function ($item) {
                    return $item['Id'];
                }, $customers['customers']);
                     
                $inString = '"'.implode('","',$tmpCustomerIds).'"';
                $select .= " where CustomerId in ($inString) ";                
            }

            $sql = $select.$model->getSortSql();
            //echo $sql;
            $allrecords = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);

            $sql = $select.$model->getSql();
            //echo $sql;
            $packs = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
            
            $data = [
                'TotalRows' => count($allrecords),
                'ListPacks' => $packs
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

    function getPackDetail($boxId) 
    {
        try
        {
            $filters = array('id' => $boxId);

            $sql = "SELECT * FROM crm_box d
                    LEFT JOIN crm_boxdetail dd ON d.Id = dd.boxId 
                    WHERE d.Id = ?";

            //echo $sql;
            $packs = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
            
            return $packs;
            
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