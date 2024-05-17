<?php
namespace App\ModelsSupport;

use App\Databases\DbConnectionProvider;
/**
 * Description of OrderRepositoty
 *
 * @author b.pelko
 */
class OrderFactory {

    /**
     * @var DbConnectionProvider
     */
    private $dbConn;
    private $logger;
    private $order_table = 'crm_order';
    private $orderdetail_table = 'crm_orderdetail';

    
    function __construct(DbConnectionProvider $dbConn) {
        $this->dbConn = $dbConn;
        $this->logger = $dbConn->getLogger();
    }
    
    function processUpdateOrders($orders) {
        try
        {
            foreach ($orders as $order) {
                if ($this->dbConn->checkExists($this->order_table, array('OrderId' => $order['OrderId']))) {
                    $arrayUpdate = [
                        'OrderNumber' => $order['OrderNumber'],
                        'OrderDate' => $order['OrderDate'],
                        'CustomerId' => $order['CustomerId'],
                        'CustomerName' => $order['CustomerName'],
                        'ExternalCode' => $order['ExternalCode'],
                        'Quantity' => $order['Quantity'],
                        'ProductTypeName' => $order['ProductTypeName'],
                        'ProcessAlgorythm' => $order['ProcessAlgorythm'],
                        'DistrictCode' => $order['DistrictCode'],
                        'AdministrativeUnitName' => $order['AdministrativeUnitName'],
                        'AdministrativeUnitSticker' => $order['AdministrativeUnitSticker'],
                        'IntervalFrom' => $order['IntervalFrom'],
                        'IntervalTo' => $order['IntervalTo'],
                        'State' => $order['State'],
                        'OrderType' => $order['OrderType'],
                        'updated_at' => date("Y-m-d H:i:s")
                    ];

                    $this->dbConn->update($this->order_table, $arrayUpdate, 'OrderId', $order['OrderId']);

                } else {
                    $arrayInsert = [
                        'OrderId' => $order['OrderId'],
                        'OrderNumber' => $order['OrderNumber'],
                        'OrderDate' => $order['OrderDate'],
                        'CustomerId' => $order['CustomerId'],
                        'CustomerName' => $order['CustomerName'],
                        'ExternalCode' => $order['ExternalCode'],
                        'Quantity' => $order['Quantity'],
                        'ProductTypeName' => $order['ProductTypeName'],
                        'ProcessAlgorythm' => $order['ProcessAlgorythm'],
                        'DistrictCode' => $order['DistrictCode'],
                        'AdministrativeUnitName' => $order['AdministrativeUnitName'],
                        'AdministrativeUnitSticker' => $order['AdministrativeUnitSticker'],
                        'IntervalFrom' => $order['IntervalFrom'],
                        'IntervalTo' => $order['IntervalTo'],
                        'State' => $order['State'],
                        'OrderType' => $order['OrderType'],
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s")
                    ];
                    $this->dbConn->insert($this->order_table, $arrayInsert);
                }   
            }
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

    function processUpdateOrderDetails($orderDetails) {
        try
        {
            foreach ($orderDetails as $order) {
                //echo $order['Id'];
                if ($this->dbConn->checkExists($this->orderdetail_table, array('OrderId' => $order['Id']))) {
                    //echo 'update';
                    $arrayUpdate = [
                        //'NumberSpaces' => json_encode($order['NumberSpaces'], true),
                        'DispatchIds' => json_encode($order['DispatchIds'], true),
                        'ProductionOrders' => json_encode($order["ProductionOrders"], true),
                        'updated_at' => date("Y-m-d H:i:s")
                    ];

                    $this->dbConn->update($this->orderdetail_table, $arrayUpdate, 'OrderId', $order['Id']);

                } else {
                    //echo 'insert';
                    $arrayInsert = [
                        'OrderId' => $order['Id'],
                        //'NumberSpaces' => json_encode($order['NumberSpaces'], true),
                        'DispatchIds' => json_encode($order['DispatchIds'], true),
                        'ProductionOrders' => json_encode($order["ProductionOrders"], true),
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s")
                    ];
                    $this->dbConn->insert($this->orderdetail_table, $arrayInsert);
                }   
            }
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
