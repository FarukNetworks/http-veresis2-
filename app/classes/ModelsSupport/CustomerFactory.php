<?php

namespace App\ModelsSupport;



use App\Databases\DBConnectionProvider;



class CustomerFactory 

{

	private $dbConn;

	private $logger;

	private $customer_table = 'crm_customer';



	function __construct(DBConnectionProvider $dbConn) {

		$this->dbConn = $dbConn;

		$this->logger = $dbConn->getLogger();

	}



	function createUpdateCustomers($customers)

	{

		try

        {

            foreach ($customers as $customer) {

                if ($this->dbConn->checkExists($this->customer_table, array('Id' => $customer['Id']))) {

                    $arrayUpdate = [

                        'Name' => $customer['Name'],

                        'ExternalName' => $customer['ExternalName'],

                        'ExternalCode' => $customer['ExternalCode'],                        

                        'StreetName' => $customer['StreetName'],

                        'StreetNumber' => $customer['StreetNumber'],

                        'PostNumber' => $customer['PostNumber'],

                        'Street' => $customer['Street'],

                        'Post' => $customer['Post'],

                        'Email' => $customer['Email'],

                        'Description' => $customer['Description'],

                        'DistrictCode' => $customer['DistrictCode'],

                        'ParentId' => $customer["ParentCustomerId"],

                        'IsActive' => ($customer['IsActive'] == true) ? 1 : 0,

                        'updated_at' => date("Y-m-d H:i:s")

                    ];



                    $this->dbConn->update($this->customer_table, $arrayUpdate, 'Id', $customer['Id']);



                } else {

                    $arrayInsert = [

                        'Id' => $customer['Id'],

                        'Name' => $customer['Name'],

                        'ExternalName' => $customer['ExternalName'],

                        'ExternalCode' => $customer['ExternalCode'],                        

                        'StreetName' => $customer['StreetName'],

                        'StreetNumber' => $customer['StreetNumber'],

                        'PostNumber' => $customer['PostNumber'],

                        'Street' => $customer['Street'],

                        'Post' => $customer['Post'],

                        'Email' => $customer['Email'],

                        'Description' => $customer['Description'],

                        'DistrictCode' => $customer['DistrictCode'],

                        'ParentId' => $customer["ParentCustomerId"],

                        'IsActive' => ($customer['IsActive'] == true) ? 1 : 0,

                        'created_at' => date("Y-m-d H:i:s"),

                        'updated_at' => date("Y-m-d H:i:s")

                    ];

                    $this->dbConn->insert($this->customer_table, $arrayInsert);

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