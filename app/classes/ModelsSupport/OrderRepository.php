<?php
namespace App\ModelsSupport;

use App\Databases\DbConnectionProvider;
use App\SearchModels\OrderSearchModel;
use \PDO;


class OrderRepository {

	private $dbConn;
	private $logger;

	function __construct(DbConnectionProvider $dbConn) {
        $this->dbConn = $dbConn;
        $this->logger = $dbConn->getLogger();
    }

    function getOrders(OrderSearchModel $model, $customers) 
    {
    	try
        {
            // var_dump($model);

            $select = "SELECT * FROM crm_order where 1=1 ";
            
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

            if(!empty($model->orderStateSelection)) {
                $array = explode(",", $model->orderStateSelection);
                //var_dump($array);

                $inString = implode(',', array_fill(0, count($array), '?'));
                $filters = array_merge($filters, $array);

                $select .=  " AND (State in ($inString)) ";
            } else {
                $select .= "";
            }
            if(!empty($model->orderTypeSelection)) {
                $array = explode(",", $model->orderTypeSelection);
                //var_dump($array);

                $inString = implode(',', array_fill(0, count($array), '?'));
                $filters = array_merge($filters, $array);

                $select .=  " AND (OrderType in ($inString)) ";
            } else {
                $select .= "";
            }

            $sort = $model->getSortSql();
            $sql = $select.$sort["sortsql"];
            
            $allrecords = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
                   
            $paging = $model->getSql();
            $sql = $select. $paging["sortsql"].$paging["pagingsql"];
            $filters = array_merge($filters, $paging["pagingparams"]);
            
            $orders = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
            
            $data = [
                'TotalRows' => count($allrecords),
                'ListOrders' => $orders
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

    function getOrderDetail($orderId, $dispatchRepository) 
    {
    	try
        {
            $filters = array('id' => $orderId);


            $sql = "SELECT * FROM crm_order d
                    LEFT JOIN crm_orderdetail dd ON d.orderId = dd.orderId 
                    WHERE d.orderId = ?";

            // echo $sql;
            $orders = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
            
            $dispatches = $dispatchRepository->getDispatchesForOrder(json_decode($orders[0]['DispatchIds'], true));

            $tmp = [
                'orders' => $orders,
                'dispatches' => $dispatches
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

    function allowShowOrder($orderid, $userCustomerId, $allowedCustomerIds) {

        try
        {
            $filters = array('id' => $orderid, 'customerId' => $userCustomerId);


            $sql = "SELECT d.orderId FROM crm_order d
                    WHERE d.orderId = ? and d.customerId = ? ";

            if(!empty($allowedCustomerIds)) {
                foreach($allowedCustomerIds as $id){    
                    $sql .= "or d.customerId = ? ";
                

                array_push($filters, $id);

                }
            }

            // echo $sql;
            $orders = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);
            // var_dump($orders);
            if (count($orders) > 0)
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

    function getOrderCustomerId($orderid) {

        try
        {
            $filters = array('orderId' => $orderid);


            $sql = "SELECT d.customerId FROM crm_order d
                    WHERE d.orderId = ?";

            // echo $sql;
            $customerId = $this->dbConn->fetch_custom($sql, $filters, \PDO::FETCH_ASSOC);

            return $customerId[0]['customerId'];
            // var_dump($orders);
            
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