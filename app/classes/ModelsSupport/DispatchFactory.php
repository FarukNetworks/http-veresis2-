<?php

namespace App\ModelsSupport;



use App\Databases\DbConnectionProvider;



class DispatchFactory 

{

	/**

     * @var DbConnectionProvider

     */

    private $dbConn;

    private $logger;

    private $dispatch_table = 'crm_dispatch';

    private $dispatchdetail_table = 'crm_dispatchdetail';



    

    function __construct(DbConnectionProvider $dbConn) {

        $this->dbConn = $dbConn;

        $this->logger = $dbConn->getLogger();

    }

    

    function processUpdateDispatches($dispatches) {

        try

        {

            foreach ($dispatches as $dispatch) {

                if ($this->dbConn->checkExists($this->dispatch_table, array('Id' => $dispatch['Id']))) {

                    $arrayUpdate = [

                        'DispatchNumber' => $dispatch['SerialNumber'],

                        'DispatchDate' => $dispatch['CreatedTime'],

                        'CustomerId' => $dispatch['CustomerId'],

                        'CustomerName' => $dispatch['CustomerName'],

                        'State' => $dispatch['State'],

                        'DispatchByPost' => ($dispatch['DispatchByPost'] == true) ? 1 : 0,

                        'DeliveryNoteErpCode' => $dispatch['DeliveryNoteErpCode'],

                        'DeliveryNoteDate' => $dispatch['DeliveryNoteDate'],

                        'CancelTime' => $dispatch['CancelTime'],

                        'CancelReason' => $dispatch['CancelReason'],

                        'updated_at' => date("Y-m-d H:i:s")

                    ];



                    $this->dbConn->update($this->dispatch_table, $arrayUpdate, 'Id', $dispatch['Id']);



                } else {

                    $arrayInsert = [

                        'Id' => $dispatch['Id'],

                        'DispatchNumber' => $dispatch['SerialNumber'],

                        'DispatchDate' => $dispatch['CreatedTime'],

                        'CustomerId' => $dispatch['CustomerId'],

                        'CustomerName' => $dispatch['CustomerName'],

                        'State' => $dispatch['State'],

                        'DispatchByPost' => ($dispatch['DispatchByPost'] == true) ? 1 : 0,

                        'DeliveryNoteErpCode' => $dispatch['DeliveryNoteErpCode'],

                        'DeliveryNoteDate' => $dispatch['DeliveryNoteDate'],

                        'CancelTime' => $dispatch['CancelTime'],

                        'CancelReason' => $dispatch['CancelReason'],

                        'created_at' => date("Y-m-d H:i:s"),

                        'updated_at' => date("Y-m-d H:i:s")

                    ];

                    $this->dbConn->insert($this->dispatch_table, $arrayInsert);

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



    function processUpdateDispatchDetails($dispatchDetails) 

    {

    	try

        {

            foreach ($dispatchDetails as $detail) {

                if ($this->dbConn->checkExists($this->dispatchdetail_table, array('DispatchId' => $detail['Id']))) {

                    $arrayUpdate = [

                        'BoxList' => json_encode($detail['BoxList'], true),

                        'ProductionOrdersList' => json_encode($detail['ProductionOrdersList'], true),

                        'updated_at' => date("Y-m-d H:i:s")

                    ];



                    $this->dbConn->update($this->dispatchdetail_table, $arrayUpdate, 'DispatchId', $detail['Id']);



                } else {

                    $arrayInsert = [

                        'DispatchId' => $detail['Id'],

                        'BoxList' => json_encode($detail['BoxList'], true),

                        'ProductionOrdersList' => json_encode($detail['ProductionOrdersList'], true),

                        'created_at' => date("Y-m-d H:i:s"),

                        'updated_at' => date("Y-m-d H:i:s")

                    ];

                    $this->dbConn->insert($this->dispatchdetail_table, $arrayInsert);

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