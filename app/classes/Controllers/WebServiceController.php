<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Helpers\JsonRenderer;
use App\Helpers\JsonRequest;
use App\Databases\DbConnectionProvider;
use App\Databases\DbTransactionHandler;

/**
 * Description of WebServiceController
 *
 * @author b.pelko
 */
class WebServiceController {

    /**
     * @var JsonRenderer
     */
    private $jsonRender;

    /**
     * @var JsonRequest
     */
    private $jsonRequest;

    private $logger;

    /**
     * @var DbConnectionProvider
     */
    private $dbConnProv;
    
    private $session;


    /**
     *
     * @var DbTransactionHandler
     */
    private $dbTranHandler;

    public function __construct(DbConnectionProvider $dbConnProv, JsonRequest $jsonRequest, JsonRenderer $jsonRender) {
        
        $this->dbConnProv = $dbConnProv;        
        $this->logger = $dbConnProv->getLogger();
        $this->jsonRequest = $jsonRequest;
        $this->jsonRender = $jsonRender;
        $this->dbTranHandler = new DbTransactionHandler($dbConnProv, $this->logger);
        $this->session = new \App\Helpers\Session();
    }      

    public function getUsers(Request $request, Response $response)
    {
        $userRepo = new \APP\ModelsSupport\UserRepository($this->dbConnProv);
        $data = $userRepo->getUsers();
        return json_encode($data);
    }
    
    public function resetPassword(Request $request, Response $response) 
    {
        $postParams = $request->getParsedBody();
        if (!empty($postParams)) {
            $userfactory = new \APP\ModelsSupport\UserFactory($this->dbConnProv, $this->session);
            $userfactory->changePasswordFromServis($postParams);
            $this->logger->info("WebServiceController - resetpasswordCmd - Success: ".json_encode($postParams));
            
            $data = ['status' => 'success', 'message' => 'Change password to user with Id: '.$postParams["Id"] .' is successfully process'];
            $this->jsonRender->render($response, 200, $data);
        }
    } 

    public function helloWorld(Request $request, Response $response) 
    {
        $data = ['status' => 'success', 'message' => 'Hello World!!!1'];
        $this->jsonRender->render($response, 200, $data);
    }	
	
	public function helloWorldPost(Request $request, Response $response) 
    {
        $data = ['status' => 'success', 'message' => 'Hello World Posted!!!1'];
        $this->jsonRender->render($response, 200, $data);
    }	
    
    public function updateUser(Request $request, Response $response) 
    {
        $postParams = $request->getParsedBody();
        $this->logger->info("WebServiceController - updatecustomerusers - Input data: ".json_encode($postParams));
        if (!empty($postParams)) {
            $userfactory = new \APP\ModelsSupport\UserFactory($this->dbConnProv, $this->session);
            $userfactory->createProfessionalUser($postParams);
            
            $this->logger->info("WebServiceController - updatecustomerusers - Success: ".json_encode($postParams));
            
            $data = ['status' => 'success', 'message' => 'updatecustomerusers users is successfully process'];
            $this->jsonRender->render($response, 200, $data);
        }
    }

    public function deleteUser(Request $request, Response $response) {
        $postParams = $request->getParsedBody();
        $this->logger->info("WebServiceController - deletecustomerusers - Input data: ".json_encode($postParams));
        if (!empty($postParams)) {
            
            $userfactory = new \APP\ModelsSupport\UserFactory($this->dbConnProv, $this->session);
            $userfactory->deleteCrmUser($postParams);
            
            $this->logger->info("WebServiceController - deletecustomerusers - Success: ".json_encode($postParams));
            
            $data = ['status' => 'success', 'message' => 'deletecustomerusers users is successfully process'];
            $this->jsonRender->render($response, 200, $data);
        }
    }
    
    function downloadConfirm(Request $request, Response $response) {
        $postParams = $request->getParsedBody();
        $users = (isset($postParams["Users"])) ? $postParams["Users"] : array();
        
        $this->logger->info("WebServiceController - downloadConfirm - Input data: ".json_encode($postParams));
        
        $userRepo = new \APP\ModelsSupport\UserRepository($this->dbConnProv);
                
        $userRepo->confirmDownloaded($users);
        
        $data = ['status' => 'success', 'message' => 'OK'];
        $this->logger->info("WebServiceController - downloadconfirmation - Success: ".json_encode($data));
        
        $this->jsonRender->render($response, 200, $data);
    }

    function updateOrders(Request $request, Response $response) {
        $postParams = $request->getParsedBody();
        $this->logger->info("WebServiceController - updateOrders - Input data: ".json_encode($postParams));
        
        $orderRepo = new \APP\ModelsSupport\OrderFactory($this->dbConnProv);
        $orderRepo->processUpdateOrders($postParams);
        
        $data = ['status' => 'success', 'message' => 'OK'];
        $this->logger->info("WebServiceController - updateOrders - Success: ".json_encode($data));
        
        $this->jsonRender->render($response, 200, $data);

    }

    function updateOrderDetails(Request $request, Response $response) {
        $postParams = $request->getParsedBody();
        $this->logger->info("WebServiceController - updateOrderDetails - Input data: ".json_encode($postParams));
        
        $orderRepo = new \APP\ModelsSupport\OrderFactory($this->dbConnProv);
        $orderRepo->processUpdateOrderDetails($postParams);      
        
        $data = ['status' => 'success', 'message' => 'OK'];
        $this->logger->info("WebServiceController - updateOrderDetails - Success: ".json_encode($data));
        
        $this->jsonRender->render($response, 200, $data);
    }

    function updateCustomers(Request $request, Response $response) {
        $postParams = $request->getParsedBody();
        $this->logger->info("WebServiceController - updateCustomers - Input data: ".json_encode($postParams));
        
        $customerRepo = new \App\ModelsSupport\CustomerFactory($this->dbConnProv);
        $customerRepo->createUpdateCustomers($postParams);

        
        $data = ['status' => 'success', 'message' => 'OK'];
        $this->logger->info("WebServiceController - updateCustomers - Success: ".json_encode($data));
        
        $this->jsonRender->render($response, 200, $data);
    }

    function updateDispatches(Request $request, Response $response) 
    {
        $postParams = $request->getParsedBody();
        $this->logger->info("WebServiceController - updateDispatches - Input data: ".json_encode($postParams));
        
        $dispRepo = new \App\ModelsSupport\DispatchFactory($this->dbConnProv);
        $dispRepo->processUpdateDispatches($postParams);

        
        $data = ['status' => 'success', 'message' => 'OK'];
        $this->logger->info("WebServiceController - updateDispatches - Success: ".json_encode($data));
        
        $this->jsonRender->render($response, 200, $data);
    }

    function updateDispatchDetails(Request $request, Response $response)
    {
        $postParams = $request->getParsedBody();
        $this->logger->info("WebServiceController - updateDispatchDetails - Input data: ".json_encode($postParams));
        
        $dispRepo = new \App\ModelsSupport\DispatchFactory($this->dbConnProv);
        $dispRepo->processUpdateDispatchDetails($postParams);

        
        $data = ['status' => 'success', 'message' => 'OK'];
        $this->logger->info("WebServiceController - updateDispatchDetails - Success: ".json_encode($data));
        
        $this->jsonRender->render($response, 200, $data);
    }

    function updateBoxes(Request $request, Response $response) 
    {
        $postParams = $request->getParsedBody();
        $this->logger->info("WebServiceController - updateBoxes - Input data: ".json_encode($postParams));
        
        $boxRepo = new \App\ModelsSupport\BoxFactory($this->dbConnProv);
        $boxRepo->processUpdateBoxes($postParams);

        
        $data = ['status' => 'success', 'message' => 'OK'];
        $this->logger->info("WebServiceController - updateBoxes - Success: ".json_encode($data));
        
        $this->jsonRender->render($response, 200, $data);
    }

    function updateBoxDetails(Request $request, Response $response)
    {
        $postParams = $request->getParsedBody();
        $this->logger->info("WebServiceController - updateBoxDetails - Input data: ".json_encode($postParams));
        
        $boxRepo = new \App\ModelsSupport\BoxFactory($this->dbConnProv);
        $boxRepo->processUpdateBoxDetails($postParams);

        
        $data = ['status' => 'success', 'message' => 'OK'];
        $this->logger->info("WebServiceController - updateBoxDetails - Success: ".json_encode($data));
        
        $this->jsonRender->render($response, 200, $data);
    }
}