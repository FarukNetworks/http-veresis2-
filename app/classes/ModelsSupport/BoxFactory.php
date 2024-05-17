<?php

namespace App\ModelsSupport;



use App\Databases\DbConnectionProvider;



class BoxFactory 

{

	/**

     * @var DbConnectionProvider

     */

    private $dbConn;

    private $logger;

    private $box_table = 'crm_box';

    private $boxdetail_table = 'crm_boxdetail';



    

    function __construct(DbConnectionProvider $dbConn) {

        $this->dbConn = $dbConn;

        $this->logger = $dbConn->getLogger();

    }

    

    function processUpdateBoxes($boxes) {

        try

        {

            foreach ($boxes as $box) {

                if ($this->dbConn->checkExists($this->box_table, array('Id' => $box['Id']))) {

                    $arrayUpdate = [

                        'Code' => $box['Code'],

                        'Date' => $box['Date'],

                        'BoxState' => $box['State'],

                        'IsCustom' => ($box['IsCustom'] == true) ? 1 : 0,

                        'PlannedFreePart' => $box['PlannedFreePart'],

                        'ActualFreePart' => $box['ActualFreePart'],

                        'CustomerId' => $box['CustomerId'],

                        'CustomerName' => $box['CustomerName'],

                        'BoxTypeDescription' => $box['BoxType'],

                        'DispatchByPost' => ($box['DispatchByPost'] == true) ? 1 : 0,

                        'PostalLabelCode' => $box['PostalLabelCode'],

                        'DispatchNumber' => $box['DispatchNumber'],

                        'DispatchId' => $box['DispatchId'],

                        'updated_at' => date("Y-m-d H:i:s")

                    ];



                    $this->dbConn->update($this->box_table, $arrayUpdate, 'Id', $box['Id']);



                } else {

                    $arrayInsert = [

                        'Id' => $box['Id'],

                        'Code' => $box['Code'],

                        'Date' => $box['Date'],

                        'BoxState' => $box['State'],

                        'IsCustom' => ($box['IsCustom'] == true) ? 1 : 0,
                        'PlannedFreePart' => $box['PlannedFreePart'],

                        'ActualFreePart' => $box['ActualFreePart'],

                        'CustomerId' => $box['CustomerId'],

                        'CustomerName' => $box['CustomerName'],

                        'BoxTypeDescription' => $box['BoxType'],

                        'DispatchByPost' => ($box['DispatchByPost'] == true) ? 1 : 0,

                        'PostalLabelCode' => $box['PostalLabelCode'],

                        'DispatchNumber' => $box['DispatchNumber'],

                        'DispatchId' => $box['DispatchId'],

                        'created_at' => date("Y-m-d H:i:s"),

                        'updated_at' => date("Y-m-d H:i:s")

                    ];

                    $this->dbConn->insert($this->box_table, $arrayInsert);

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



    function processUpdateBoxDetails($boxDetails) 

    {

    	try

        {

            foreach ($boxDetails as $detail) {

                if ($this->dbConn->checkExists($this->boxdetail_table, array('BoxId' => $detail['BoxId']))) {

                    $arrayUpdate = [

                        'BoxContent' => json_encode($detail['BoxContent'], true),

                        'updated_at' => date("Y-m-d H:i:s")

                    ];



                    $this->dbConn->update($this->boxdetail_table, $arrayUpdate, 'BoxId', $detail['BoxId']);



                } else {

                    $arrayInsert = [

                        'BoxId' => $detail['BoxId'],

                        'BoxContent' => json_encode($detail['BoxContent'], true),

                        'created_at' => date("Y-m-d H:i:s"),

                        'updated_at' => date("Y-m-d H:i:s")

                    ];

                    $this->dbConn->insert($this->boxdetail_table, $arrayInsert);

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