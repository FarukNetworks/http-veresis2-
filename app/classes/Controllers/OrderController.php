<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use App\Databases\DbConnectionProvider;
use App\Helpers\Uri;


class OrderController 
{
	private $view;
	private $authProvider;
	private $orderRepository;
    private $customerRepository;
    private $dispatchRepository;
    private $boxRepository;
    private $packRepository;
    private $userRepository;

	function __construct($app) {
		$container = $app->getContainer();
		$this->dbConnProv = $container->get('dbConn');
        $this->view = $container->get('view');
        $this->authProvider = $app->getContainer()->get('authProvider');
        $this->orderRepository = new \App\ModelsSupport\OrderRepository($this->dbConnProv);
        $this->customerRepository = new \App\ModelsSupport\CustomerRepository($this->dbConnProv);
        $this->dispatchRepository = new \App\ModelsSupport\DispatchRepository($this->dbConnProv);
        $this->boxRepository = new \App\ModelsSupport\BoxRepository($this->dbConnProv);
        $this->packRepository = new \App\ModelsSupport\PackRepository($this->dbConnProv);
        $this->userRepository = new \App\ModelsSupport\UserRepository($this->dbConnProv);
	}

	function isUserAuthenticate() {
		$authData = $this->authProvider->isUserLoggedIn();
        return $authData;

	}

    function indexHome(Request $request, Response $response) {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            //$currentUrl = APPBASEURL;
            return $response->withRedirect(APPBASEURL.'prijava');
        }

        $userData = $this->userRepository->getUserById($authData['userid']);
        if($userData["canViewOrders"] == "orders") {
            return $response->withRedirect(APPBASEURL.'narocila');
        } else {
            return $response->withRedirect(APPBASEURL.'unicene-tablice');
        }
    }

    function checkIfUserCanViewOrders($userid) {

        $userData = $this->userRepository->getUserById($userid);
  
        if($userData["canViewOrders"] == "all") {
            throw new \Exception('Dostop zavrnjen!');
        }
    }

    function checkIfUserIsAllowedToView($userid, $customerid) {

        if($customerid) {
            $userData = $this->userRepository->getUserById($userid);

            $allowedCustomerIds = array();

            $allowedCustomerIds = $this->userRepository->getAllowedCustomersId($userData["customerId"]);

            // var_dump($allowedCustomerIds);

            // var_dump($userData['canViewSubUnits']);

            if(!empty($allowedCustomerIds) AND $userData['canViewSubUnits']) {

                array_push($allowedCustomerIds, $userData['customerId']);

                if(!in_array($customerid, $allowedCustomerIds)) {

                    throw new \Exception('Dostop zavrnjen!');

                }

            } else {
                if($customerid !== $userData["customerId"]) {
                    throw new \Exception('Dostop zavrnjen!');
                }
            }
        }

    }

    function getCustomers(Request $request, Response $response) 
    {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $data = $this->customerRepository->getCustomers($authData['userid']);
        return json_encode($data);
    }

	function indexOrders(Request $request, Response $response)
	{
		$authData = $this->isUserAuthenticate();
        if (empty($authData)) {

        	$currentUrl = APPBASEURL."narocila";
            return $response->withRedirect(APPBASEURL.'prijava?retUrl='.$currentUrl);
        }

        $userData = $this->userRepository->getUserById($authData['userid']);
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }

        $data = [
            'authdata' => $authData,
            'activepage' => 'narocila',
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];
                
        return $this->view->render($response, 'orders/orders.twig',$data);
	}

	function indexOrder(Request $request, Response $response, $args) 
	{
		$guid = new \App\Helpers\GuidGenerator();

		$isParameterOrderIdGuid = $guid->validationGuid($args['id']);
		if (!$isParameterOrderIdGuid) {
            return $response->withRedirect(APPBASEURL.'notfound');
			// throw new \Exception("Orderid is not valid Guid !");
		}
		$authData = $this->isUserAuthenticate();
        
        if (empty($authData)) {

        	$currentUrl = APPBASEURL."narocila/".$args['id'];
            return $response->withRedirect(APPBASEURL.'prijava?retUrl='.$currentUrl);
        }

        $userData = $this->userRepository->getUserById($authData['userid']);
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }

        /* preverjanje će lahko uporabnik dostopa do določenega naročila */

        $allowedCustomerIds = array();
        

        if($userData['canViewSubUnits']) {

            /* pridobi customerId od orderId */
            $customerIdFromOrderId = $this->orderRepository->getOrderCustomerId($args['id']);

            /* pridobi vse Id-je podenot */
            $allowedCustomerIds = $this->userRepository->getAllowedCustomersId($userData["customerId"]);

            $tmpAllowedCustomerIds = $allowedCustomerIds;
            array_push($tmpAllowedCustomerIds, $userData["customerId"]);

            if(!in_array($customerIdFromOrderId, $tmpAllowedCustomerIds)) {

                return $response->withRedirect(APPBASEURL.'notfound');

            }


        }


        $allowShowOrder = $this->orderRepository->allowShowOrder($args["id"], $userData["customerId"], $allowedCustomerIds);

        if (!$allowShowOrder) {
            return $response->withRedirect(APPBASEURL.'notfound');
        }

        /* konec preverjanja */




        $data = [
            'authdata' => $authData,
            'activepage' => 'narocila',
            'orderId' => $args['id'],
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];
                
        return $this->view->render($response, 'orders/order.twig', $data);
	}

	function getOrders(Request $request, Response $response) 
	{
		$authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            // return $response->withRedirect(APPBASEURL.'notfound');
        	throw new \Exception('Dostop zavrnjen!');
        }

        $this->checkIfUserCanViewOrders($authData['userid']);

        $params = $request->getQueryParams();

        if(!array_key_exists('customerId', $params)) $params['customerId'] = null;


        $this->checkIfUserIsAllowedToView($authData['userid'], $params['customerId']);

        
        // var_dump($params);
        $model = new \App\SearchModels\OrderSearchModel($params);
        //var_dump($model);

        $customers = $this->customerRepository->getCustomers($authData['userid']);
        //var_dump($customers);

        $data = $this->orderRepository->getOrders($model, $customers);
        //var_dump($data);
        return json_encode($data);
	}

	function getOrder(Request $request, Response $response, $args) 
	{
		$authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            // return $response->withRedirect(APPBASEURL.'notfound');
        	throw new \Exception('Dostop zavrnjen!');
        }
        $this->checkIfUserCanViewOrders($authData['userid']);

        $guid = new \App\Helpers\GuidGenerator();
        $orderId = $args['id'];
        $isParameterOrderIdGuid = $guid->validationGuid($orderId);
        if (!$isParameterOrderIdGuid) {
            // return $response->withRedirect(APPBASEURL.'notfound');
            throw new \Exception("id is not valid Guid !");
        }

        $userData = $this->userRepository->getUserById($authData['userid']);
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL.'notfound');
            // throw new \Exception('Dostop zavrnjen!');
        }



        /* preverjanje će lahko uporabnik dostopa do določenega naročila */

        $allowedCustomerIds = array();
        

        if($userData['canViewSubUnits']) {

            /* pridobi customerId od orderId */
            $customerIdFromOrderId = $this->orderRepository->getOrderCustomerId($args['id']);

            /* pridobi vse Id-je podenot */
            $allowedCustomerIds = $this->userRepository->getAllowedCustomersId($userData["customerId"]);

            $tmpAllowedCustomerIds = $allowedCustomerIds;
            array_push($tmpAllowedCustomerIds, $userData["customerId"]);

            if(!in_array($customerIdFromOrderId, $tmpAllowedCustomerIds)) {
                // return $response->withRedirect(APPBASEURL.'notfound');
                throw new \Exception('Dostop zavrnjen!');

            }

        }


        $allowShowOrder = $this->orderRepository->allowShowOrder($args["id"], $userData["customerId"], $allowedCustomerIds);

        if (!$allowShowOrder) {
            // return $response->withRedirect(APPBASEURL.'notfound');
            throw new \Exception('Dostop zavrnjen!');
        }

        /* konec preverjanja */




        $data = $this->orderRepository->getOrderDetail($orderId, $this->dispatchRepository); 
        
        return json_encode($data);
	}
    
	function indexDispatches(Request $request, Response $response)
	{
		$authData = $this->isUserAuthenticate();
        if (empty($authData)) {

        	$currentUrl = APPBASEURL."odpreme";
            return $response->withRedirect(APPBASEURL.'prijava?retUrl='.$currentUrl);
        }

        $userData = $this->userRepository->getUserById($authData['userid']);
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }

        $data = [
            'authdata' => $authData,
            'activepage' => 'odpreme',
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];
                
        return $this->view->render($response, 'dispatches/dispatches.twig',$data);
	}

	function indexDispatch(Request $request, Response $response, $args) 
	{
		$guid = new \App\Helpers\GuidGenerator();
		$isParameterOrderIdGuid = $guid->validationGuid($args['id']);
		if (!$isParameterOrderIdGuid) {
            return $response->withRedirect(APPBASEURL.'notfound');
			// throw new \Exception("Orderid is not valid Guid !");
		}
		$authData = $this->isUserAuthenticate();
        if (empty($authData)) {

        	$currentUrl = APPBASEURL."odpreme/".$args['id'];
            return $response->withRedirect(APPBASEURL.'prijava?retUrl='.$currentUrl);
        }	
	    
        $userData = $this->userRepository->getUserById($authData['userid']);   
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }



        /* preverjanje će lahko uporabnik dostopa do določene odpreme */

        $allowedCustomerIds = array();
        

        if($userData['canViewSubUnits']) {

            /* pridobi customerId od orderId */
            $customerIdFromDispatchId = $this->dispatchRepository->getDispatchCustomerId($args['id']);

            /* pridobi vse Id-je podenot */
            $allowedCustomerIds = $this->userRepository->getAllowedCustomersId($userData["customerId"]);

            $tmpAllowedCustomerIds = $allowedCustomerIds;
            array_push($tmpAllowedCustomerIds, $userData["customerId"]);

            if(!in_array($customerIdFromDispatchId, $tmpAllowedCustomerIds)) {

                return $response->withRedirect(APPBASEURL.'notfound');

            }


        }


        $allowShowDispatch = $this->dispatchRepository->allowShowDispatch($args["id"], $userData["customerId"], $allowedCustomerIds);

        if (!$allowShowDispatch) {
            return $response->withRedirect(APPBASEURL.'notfound');
        }

        /* konec preverjanja */

        $data = [
            'authdata' => $authData,
            'activepage' => 'odpreme',
            'dispatchId' => $args['id'],
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];
                
        return $this->view->render($response, 'dispatches/dispatch.twig',$data);
	}

    function getDispatches(Request $request, Response $response) 
    {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            // throw new \Exception('Dostop zavrnjen!');
            return $response->withRedirect(APPBASEURL.'notfound');
        }

        $this->checkIfUserCanViewOrders($authData['userid']);

        $params = $request->getQueryParams();

        if(!array_key_exists('customerId', $params)) $params['customerId'] = null;

        $this->checkIfUserIsAllowedToView($authData['userid'], $params['customerId']);

        $model = new \App\SearchModels\DispatchSearchModel($params);

        $customers = $this->customerRepository->getCustomers($authData['userid']);
        $data = $this->dispatchRepository->getDispatches($model, $customers);
        return json_encode($data);
    }

    function getDispatch(Request $request, Response $response, $args) 
    {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            // return $response->withRedirect(APPBASEURL.'notfound');
            throw new \Exception('Dostop zavrnjen!');
        }

        $this->checkIfUserCanViewOrders($authData['userid']);

        $guid = new \App\Helpers\GuidGenerator();
        $dispatchId = $args['id'];
        $isParameterDispatchIdGuid = $guid->validationGuid($dispatchId);
        if (!$isParameterDispatchIdGuid) {
            // return $response->withRedirect(APPBASEURL.'notfound');
            throw new \Exception("id is not valid Guid !");
        }

        $userData = $this->userRepository->getUserById($authData['userid']);   
        if($userData["canViewOrders"] == "all") {
            // return $response->withRedirect(APPBASEURL.'notfound');
            throw new \Exception('Dostop zavrnjen!');
        }

        
        /* preverjanje će lahko uporabnik dostopa do določene odpreme */

        $allowedCustomerIds = array();
        

        if($userData['canViewSubUnits']) {

            /* pridobi customerId od orderId */
            $customerIdFromDispatchId = $this->dispatchRepository->getDispatchCustomerId($args['id']);

            /* pridobi vse Id-je podenot */
            $allowedCustomerIds = $this->userRepository->getAllowedCustomersId($userData["customerId"]);

            $tmpAllowedCustomerIds = $allowedCustomerIds;
            array_push($tmpAllowedCustomerIds, $userData["customerId"]);

            if(!in_array($customerIdFromDispatchId, $tmpAllowedCustomerIds)) {
                // return $response->withRedirect(APPBASEURL.'notfound');
                throw new \Exception('Dostop zavrnjen!');

            }
        }


        $allowShowDispatch = $this->dispatchRepository->allowShowDispatch($args["id"], $userData["customerId"], $allowedCustomerIds);

        if (!$allowShowDispatch) {
            // return $response->withRedirect(APPBASEURL.'notfound');
            throw new \Exception('Dostop zavrnjen!');
        }

        /* konec preverjanja */



        $data = $this->dispatchRepository->getDispatchDetail($dispatchId); 
        return json_encode($data);

    }

	function indexBoxes(Request $request, Response $response)
	{
		$authData = $this->isUserAuthenticate();
        if (empty($authData)) {

        	$currentUrl = APPBASEURL."skatle";
            return $response->withRedirect(APPBASEURL.'prijava?retUrl='.$currentUrl);
        }

        $userData = $this->userRepository->getUserById($authData['userid']);
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }

        $data = [
            'authdata' => $authData,
            'activepage' => 'skatle',
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];
                
        return $this->view->render($response, 'boxes/boxes.twig',$data);
	}

	function indexBox(Request $request, Response $response, $args) 
	{
		$guid = new \App\Helpers\GuidGenerator();
		$isParameterOrderIdGuid = $guid->validationGuid($args['id']);
		if (!$isParameterOrderIdGuid) {
            return $response->withRedirect(APPBASEURL.'notfound');
			// throw new \Exception("Orderid is not valid Guid !");
		}
		$authData = $this->isUserAuthenticate();
        if (empty($authData)) {

        	$currentUrl = APPBASEURL."skatle/".$args['id'];
            return $response->withRedirect(APPBASEURL.'prijava?retUrl='.$currentUrl);
        }

        $userData = $this->userRepository->getUserById($authData['userid']);	
	    if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }


        /* preverjanje će lahko uporabnik dostopa do določene škatle */

        $allowedCustomerIds = array();
        

        if($userData['canViewSubUnits']) {

            /* pridobi customerId od orderId */
            $customerIdFromBoxId = $this->boxRepository->getBoxCustomerId($args['id']);

            /* pridobi vse Id-je podenot */
            $allowedCustomerIds = $this->userRepository->getAllowedCustomersId($userData["customerId"]);

            $tmpAllowedCustomerIds = $allowedCustomerIds;
            array_push($tmpAllowedCustomerIds, $userData["customerId"]);

            if(!in_array($customerIdFromBoxId, $tmpAllowedCustomerIds)) {

                return $response->withRedirect(APPBASEURL.'notfound');
            }

        }


        $allowShowBox = $this->boxRepository->allowShowBox($args["id"], $userData["customerId"], $allowedCustomerIds);

        if (!$allowShowBox) {
            return $response->withRedirect(APPBASEURL.'notfound');
        }

        /* konec preverjanja */



        $data = [
            'authdata' => $authData,
            'activepage' => 'skatle',
            'boxId' => $args['id'],
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];
                
        return $this->view->render($response, 'boxes/box.twig',$data);
	}

    function getBoxes(Request $request, Response $response) 
    {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            // return $response->withRedirect(APPBASEURL.'notfound');
            throw new \Exception('Dostop zavrnjen!');
        }

        $this->checkIfUserCanViewOrders($authData['userid']);

        $params = $request->getQueryParams();

        if(!array_key_exists('customerId', $params)) $params['customerId'] = null;


        $this->checkIfUserIsAllowedToView($authData['userid'], $params['customerId']);

        $model = new \App\SearchModels\BoxSearchModel($params);

        $customers = $this->customerRepository->getCustomers($authData['userid']);
        $data = $this->boxRepository->getBoxes($model, $customers);
        return json_encode($data);
    }

    function getBox(Request $request, Response $response, $args) 
    {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            // return $response->withRedirect(APPBASEURL.'notfound');
            throw new \Exception('Dostop zavrnjen!');
        }

        $this->checkIfUserCanViewOrders($authData['userid']);


        $guid = new \App\Helpers\GuidGenerator();
        $boxId = $args['id'];
        $isParameterBoxIdGuid = $guid->validationGuid($boxId);
        if (!$isParameterBoxIdGuid) {
            // return $response->withRedirect(APPBASEURL.'notfound');
            throw new \Exception("id is not valid Guid !");
        }

        $userData = $this->userRepository->getUserById($authData['userid']);    
        if($userData["canViewOrders"] == "all") {
            // return $response->withRedirect(APPBASEURL.'notfound');
            throw new \Exception('Dostop zavrnjen!');
        }


        /* preverjanje će lahko uporabnik dostopa do določene škatle */

        $allowedCustomerIds = array();
        

        if($userData['canViewSubUnits']) {

            /* pridobi customerId od orderId */
            $customerIdFromBoxId = $this->boxRepository->getBoxCustomerId($args['id']);

            /* pridobi vse Id-je podenot */
            $allowedCustomerIds = $this->userRepository->getAllowedCustomersId($userData["customerId"]);

            $tmpAllowedCustomerIds = $allowedCustomerIds;
            array_push($tmpAllowedCustomerIds, $userData["customerId"]);

            if(!in_array($customerIdFromBoxId, $tmpAllowedCustomerIds)) {
                // return $response->withRedirect(APPBASEURL.'notfound');
                throw new \Exception('Dostop zavrnjen!');
            }

        }


        $allowShowBox = $this->boxRepository->allowShowBox($args["id"], $userData["customerId"], $allowedCustomerIds);

        if (!$allowShowBox) {
            // return $response->withRedirect(APPBASEURL.'notfound');
            throw new \Exception('Dostop zavrnjen!');
        }

        /* konec preverjanja */


        $data = $this->boxRepository->getBoxDetail($boxId); 
        return json_encode($data);

    }
}