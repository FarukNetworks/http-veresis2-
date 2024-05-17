<?php
namespace App\Controllers;
  
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use App\Databases\DbConnectionProvider; 
use App\Helpers\Uri;
use App\Helpers\ExportToExcelHelper;
use EasyRequest;
use EasyRequest\Client as HttpClient;


class DestroyedPlateController 
{
	private $view;
	private $authProvider;
	private $customerRepository;
    private $userRepository;
    private $settings;

	function __construct($app) {
		$container = $app->getContainer();
		$this->dbConnProv = $container->get('dbConn');
        $this->view = $container->get('view');
        $this->authProvider = $app->getContainer()->get('authProvider');
        $this->settings = $container->get('settings');
        
        $this->customerRepository = new \App\ModelsSupport\CustomerRepository($this->dbConnProv);
        $this->userRepository = new \App\ModelsSupport\UserRepository($this->dbConnProv);
	}

	function isUserAuthenticate() {
		$authData = $this->authProvider->isUserLoggedIn();
        return $authData;
	}

    private function get_Lppmapi_Settings() {
        $appEnviroment = $this->settings["app_development_mode"];
        $authSettings = $this->settings["externalApiUrl"][$appEnviroment]['lppmApiUrl'];
        return $authSettings;
    }

    private function authenticateLppmApi() {
        $settings = $this->get_Lppmapi_Settings();
	//var_dump($settings);
        $request = HttpClient::request($settings['baseUrl'].$settings['loginUrl'], 'POST', array('body_as_json' => true))->withJson($settings['loginData']);
        //var_dump($request);
	$response = $request->send();
	//var_dump($request->getError());
        

	//get response cookies
        $jar = new \EasyRequest\Cookie\CookieJar;
        $jar->fromResponse($response);
        
        $r = json_decode((string) $response->getBody());
        $r->cookie = $jar;
        
        //var_dump($r);
        return $r;
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
                    // if($redirect) return true;
                    throw new \Exception('Dostop zavrnjen!');

                }

            } else {
                if($customerid !== $userData["customerId"]) {
                    // if($redirect) return true;
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
    

    function indexPacks(Request $request, Response $response)
    {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {

            $currentUrl = APPBASEURL."paketi";
            return $response->withRedirect(APPBASEURL.'prijava?retUrl='.$currentUrl);
        }

        $userData = $this->userRepository->getUserById($authData['userid']);
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }

        $data = [
            'authdata' => $authData,
            'activepage' => 'paketi',
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];
                
        return $this->view->render($response, 'packs/packs.twig',$data);
    }

    function indexPack(Request $request, Response $response, $args) 
    {
        $guid = new \App\Helpers\GuidGenerator();
        $isParameterOrderIdGuid = $guid->validationGuid($args['id']);
        if (!$isParameterOrderIdGuid) {
            throw new \Exception("Packid is not valid Guid !");
        }
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {

            $currentUrl = APPBASEURL."paketi/".$args['id'];
            return $response->withRedirect(APPBASEURL.'prijava?retUrl='.$currentUrl);

        }

        $userData = $this->userRepository->getUserById($authData['userid']);   
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }

        $data = [
            'authdata' => $authData,
            'activepage' => 'paketi',
            'packSerialNumber' => $args['packsn'],
            'packageId' => $args['id'],
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];
                
        return $this->view->render($response, 'packs/pack.twig',$data);
    }

    function indexTablice(Request $request, Response $response, $args) 
    {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {

            $currentUrl = APPBASEURL."unicene-tablice";
            return $response->withRedirect(APPBASEURL.'prijava?retUrl='.$currentUrl);
        }

        $userData = $this->userRepository->getUserById($authData['userid']);

        $data = [
            'authdata' => $authData,
            'activepage' => 'unicene-tablice',
            'activedropdown' => 1,
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];

                
        return $this->view->render($response, 'tablice/tablice.twig',$data);
    }

    function indexPonaredki(Request $request, Response $response, $args) 
    {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {

            $currentUrl = APPBASEURL."ponaredki";
            return $response->withRedirect(APPBASEURL.'prijava?retUrl='.$currentUrl);
        }

        $userData = $this->userRepository->getUserById($authData['userid']);

        $data = [
            'authdata' => $authData,
            'activepage' => 'ponarejene-tablice',
            'activedropdown' => 1,
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];
                
        return $this->view->render($response, 'tablice/ponaredki.twig',$data);
    }


    function GetDestroyedPackages(Request $request, Response $response) {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $this->checkIfUserCanViewOrders($authData['userid']);

        $params = $request->getQueryParams();

        if($params['customerUserId'] !== $authData['userid']) {
             throw new \Exception('Dostop zavrnjen!');
        }


        if(!array_key_exists('customerId', $params)) $params['customerId'] = null;


        $this->checkIfUserIsAllowedToView($authData['userid'], $params['customerId']);

        $authExternalapi = $this->authenticateLppmApi();

        if (!$authExternalapi->isAuthenticated) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $model = new \App\SearchModels\PackSearchModel($params);
         //var_dump($model);

        $lppmPacks = $model->prepareLppmSearchModel();
        //var_dump($lppmPacks);
        $urlParams = http_build_query($lppmPacks);

        $baseLppmApi = $this->get_Lppmapi_Settings()['baseUrl'];
        $requestUrl = $baseLppmApi . 'DestroyedPackage/GetDestroyedPackages?' . $urlParams;
        // echo $requestUrl;
        $req = HttpClient::request($requestUrl, 'GET', array('cookie_jar' => $authExternalapi->cookie));
        //var_dump($req);
        $res = $req->send();
        //var_dump($res); 
        
        $r = json_decode((string) $res->getBody(), true);
        // var_dump($r);


        $data = [
            'ListPacs' => $r["rows"],
            'TotalRows' => $r["total"]
        ];
        return json_encode($data);
    }

    function ExportSelectedDestroyedPackages(Request $request, Response $response) {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $authExternalapi = $this->authenticateLppmApi();
        if (!$authExternalapi->isAuthenticated) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $postData = $request->getParsedBody();
        //var_dump($postData);

        $baseLppmApi = $this->get_Lppmapi_Settings()['baseUrl'];
        $url = $baseLppmApi.'DestroyedPackage/SendDestroyedPackagesPlatesList';
        
        $postData = $request->getParsedBody();

        $cmdModel = [
            "selectedDestroyedPackagesIds" => $postData["selectedPackagesId"]
        ];
        //var_dump($cmdModel);

        $req = HttpClient::request($url, 'POST', array('cookie_jar' => $authExternalapi->cookie, 'body_as_json' => true))->withJson($cmdModel);
        //var_dump($req);

        $res = $req->send();

        
        $responseData = json_decode((string) $res->getBody(), true);
        //var_dump($responseData);
                 
        $file = "izvozpaketov.xls";

        $newResponse = $response->withHeader('Content-Type', 'application/vnd.ms-excel')
                   ->withHeader('Content-Disposition', 'attachment;filename="'.$file.'"')
                   ->withHeader('Expires', '0')
                   ->withHeader('Cache-Control', 'must-revalidate')
                   ->withHeader('Pragma', 'public');
        
        $newResponse = $newResponse->withStatus(200);

        $data = ExportToExcelHelper::ExportDestroyedPackages($responseData);
        //$data = ExportToExcelHelper::ExportDestroyedPackages(ExportToExcelHelper::fakeDateForExport());

        $newResponse->getBody()->write($data);       

        return $newResponse;

    }

    function GetDestroyedPackageDetails(Request $request, Response $response) {
        
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            throw new \Exception('Dostop zavrnjen!');
        }
        $this->checkIfUserCanViewOrders($authData['userid']);

        $authExternalapi = $this->authenticateLppmApi();
        if (!$authExternalapi->isAuthenticated) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $userData = $this->userRepository->getUserById($authData['userid']);

        $params = $request->getQueryParams();
        $model = new \App\SearchModels\PackDetailSearchModel($params);
        $model->setCustomerId($userData["customerId"]);
        $lppmPacks = $model->prepareLppmSearchModel();
        $urlParams = http_build_query($lppmPacks);

        $baseLppmApi = $this->get_Lppmapi_Settings()['baseUrl'];
        $requestUrl = $baseLppmApi.'DestroyedPackage/GetDestroyedPackageDetails?'.$urlParams;
        // echo $requestUrl;
        $req = HttpClient::request($requestUrl, 'GET', array('cookie_jar' => $authExternalapi->cookie));
        //var_dump($req);
        $res = $req->send();
        //var_dump($res);
        
        $r = json_decode((string) $res->getBody(), true);

        // var_dump($r);

        $checkIfUserIsAllowedToView = $this->checkIfUserIsAllowedToView($authData["userid"], $r["customerId"]);

        $data = [
            'CustomerId' => $r["customerId"],
            'ListPacs' => $r["rows"],
            'TotalRows' => $r["total"]
        ];
        // if($data['TotalRows'] == 0) {
        //     throw new \Exception('Ni podatkov !');
        // }



        return json_encode($data);
    }

    function getDestroyedPlates(Request $request, Response $response) {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $authExternalapi = $this->authenticateLppmApi();
        if (!$authExternalapi->isAuthenticated) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $params = $request->getQueryParams();

        $baseLppmApi = $this->get_Lppmapi_Settings()['baseUrl'];
        $requestUrl = $baseLppmApi.'DestroyedPlate/GetDestroyedPlates?customerUserId='.$authData['userid'].'&asciiPlateNumber='.$params['asciiPlateNumber'];
        // echo $requestUrl;
        $req = HttpClient::request($requestUrl, 'GET', array('cookie_jar' => $authExternalapi->cookie));
        //var_dump($req);
        $res = $req->send();
        //var_dump($res);
        
        $r = json_decode((string) $res->getBody(), true);
        //var_dump($r);
        return json_encode($r);
    }

    function getDestroyedPlateImage(Request $request, Response $response, $args) {
        $authData = $this->isUserAuthenticate();
        // var_dump($authData);
        if (empty($authData)) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $authExternalapi = $this->authenticateLppmApi();
        if (!$authExternalapi->isAuthenticated) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $baseLppmApi = $this->get_Lppmapi_Settings()['baseUrl'];
        $requestUrl = $baseLppmApi.'DestroyedPlate/GetDestroyedPlateDetails?id='.$args['id'];
        // echo $requestUrl;
        $req = HttpClient::request($requestUrl, 'GET', array('cookie_jar' => $authExternalapi->cookie));
        //var_dump($req);
        $res = $req->send();
        //var_dump($res);
        
        $r = json_decode((string) $res->getBody(), true);  

        // var_dump($r);

        $userData = $this->userRepository->getUserById($authData['userid']);

        if($r['isFake']) {
            if(!$userData['canViewAllFakeDestroyedPlates']) {
                $this->checkIfUserIsAllowedToView($authData['userid'], $r['customerId']);
            }
        } else {
            if(!$userData['canViewAllDestroyedPlates']) {
                $this->checkIfUserIsAllowedToView($authData['userid'], $r['customerId']);
            }
        }

        if(!$userData['canViewAllDestroyedPlates']) {
            $this->checkIfUserIsAllowedToView($authData['userid'], $r['customerId']);
        }

        // var_dump($r);
        return json_encode($r);
    }

    function sendDestroyedPlates(Request $request, Response $response) {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $authExternalapi = $this->authenticateLppmApi();
        if (!$authExternalapi->isAuthenticated) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $baseLppmApi = $this->get_Lppmapi_Settings()['baseUrl'];
        
        //$url = $baseLppmApi.'DestroyedPlate/SendDestroyedPlatesOnEmail';
        $url = $baseLppmApi.'command';

        $postData = $request->getParsedBody();

        if(!$postData['destroyedPlateCustomerIds']) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $customerIds = $postData['destroyedPlateCustomerIds'];

        $isFakePlates = $postData['isFake'];

        // var_dump($isFakePlates);

        $userData = $this->userRepository->getUserById($authData['userid']);

        foreach($customerIds as $key => $id) {

            if($isFakePlates[$key]) {
                // return json_encode(array('OK za fake'));
                if(!$userData['canViewAllFakeDestroyedPlates']) {
                    $this->checkIfUserIsAllowedToView($authData['userid'], $id);
                }
            } else {
                // return json_encode(array('OK za uničene'));
                if(!$userData['canViewAllDestroyedPlates']) {
                    $this->checkIfUserIsAllowedToView($authData['userid'], $id);
                }
            }
                
        }

        // return json_encode($postData['isFake']);

        $dataForCommand = [
            "customerUserId" => $authData['userid'],
            "destroyedPlateIds" => $postData['destroyedPlateIds']
        ];

        $cmdModel = [
            "name" => "send-destroyed-plates-on-email",
            "data" => $dataForCommand
        ];

        $req = HttpClient::request($url, 'POST', array('cookie_jar' => $authExternalapi->cookie, 'body_as_json' => true))->withJson($cmdModel);
        $res = $req->send();
        return json_encode(array('OK'));
        
    }

    function getFakeDestroyedPlates(Request $request, Response $response) {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $authExternalapi = $this->authenticateLppmApi();

        if (!$authExternalapi->isAuthenticated) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $params = $request->getQueryParams();

        if($params['customerUserId'] !== $authData['userid']) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $model = new \App\SearchModels\FakePlateSearchModel($params);
        //var_dump($model);

        $lppmFakePlates = $model->prepareLppmSearchModel();
        //var_dump($lppmFakePlates);
        $urlParams = http_build_query($lppmFakePlates);
        //var_dump($urlParams);
        
        $baseLppmApi = $this->get_Lppmapi_Settings()['baseUrl'];
        $requestUrl = $baseLppmApi.'DestroyedPlate/GetFakeDestroyedPlates?'.$urlParams;
         //echo $requestUrl;
        $req = HttpClient::request($requestUrl, 'GET', array('cookie_jar' => $authExternalapi->cookie));
        //var_dump($req);
        $res = $req->send();
        //var_dump($res);
        
        $r = json_decode((string) $res->getBody(), true);
        //var_dump($r);

        // var_dump($r);

        foreach($r['customerIds'] as $id) {
            $this->checkIfUserIsAllowedToView($authData['userid'], $id);
            // echo $id;
        }
        
        $data = [
            'ListFakePlates' => $r["rows"],
            'TotalRows' => $r["total"]
        ];
        return json_encode($data);
    }

    function getSearchFakeDestroyedPlates(Request $request, Response $response) {
        $authData = $this->isUserAuthenticate();
        if (empty($authData)) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $authExternalapi = $this->authenticateLppmApi();
        if (!$authExternalapi->isAuthenticated) {
            throw new \Exception('Dostop zavrnjen!');
        }

        $params = $request->getQueryParams();

        $model = new \App\SearchModels\FakePlateSearchModel($params);
        // var_dump($model);

        $lppmFakePlates = $model->prepareLppmSearchModel();
        //var_dump($lppmFakePlates);
        $urlParams = http_build_query($lppmFakePlates);
        //var_dump($urlParams);

        $baseLppmApi = $this->get_Lppmapi_Settings()['baseUrl'];
        $requestUrl = $baseLppmApi.'DestroyedPlate/GetFakeDestroyedPlates?customerUserId='.$authData['userid'].'&asciiPlateNumber='.$params['asciiPlateNumber'].'&'.$urlParams;
        //echo $requestUrl;
        $req = HttpClient::request($requestUrl, 'GET', array('cookie_jar' => $authExternalapi->cookie));
        //var_dump($req);
        $res = $req->send();
        //var_dump($res);
        
        $r = json_decode((string) $res->getBody(), true);

        foreach($r['customerIds'] as $id) {
            $this->checkIfUserIsAllowedToView($authData['userid'], $id);
        }


        // var_dump($r);
        $data = [
            'ListFakePlates' => $r["rows"],
            'TotalRows' => $r["total"]
        ];
        return json_encode($r);
    }
}