<?php
/**
 * Register CSRF for all routes
 */
$app->add($app->getContainer()->get('csrf'));

/**
 * Security route
 * https://github.com/tuupola/slim-basic-auth
 */
$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path" => "/wsapi",
    "secure" => false,
    "users" => [
        "lppmservice" => "169026420157065c825e6402.36303375"
    ],
    "error" => function ($request, $response, $arguments) {
        $data = [];
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response->write(json_encode($response, JSON_UNESCAPED_SLASHES));
    }
]));

/**
 * Home route -----------------------------------------------------------------//
 */
$app->get('/', 'App\Controllers\OrderController:indexHome');
 
$app->get('/narocila/{id}', 'App\Controllers\OrderController:indexOrder');
$app->get('/narocila', 'App\Controllers\OrderController:indexOrders');
$app->get('/getorders', 'App\Controllers\OrderController:getOrders');
$app->get('/getorder/{id}', 'App\Controllers\OrderController:getOrder');
//$app->get('/getorderdispatch', 'App\Controllers\OrderController:getOrderDispatch');

$app->get('/getcustomers', 'App\Controllers\OrderController:getCustomers');

$app->get('/odpreme/{id}', 'App\Controllers\OrderController:indexDispatch');
$app->get('/odpreme', 'App\Controllers\OrderController:indexDispatches');
$app->get('/getdispatches', 'App\Controllers\OrderController:getDispatches');
$app->get('/getdispatch/{id}', 'App\Controllers\OrderController:getDispatch');

$app->get('/skatle/{id}', 'App\Controllers\OrderController:indexBox');
$app->get('/skatle', 'App\Controllers\OrderController:indexBoxes');
$app->get('/getboxes', 'App\Controllers\OrderController:getBoxes');
$app->get('/getbox/{id}', 'App\Controllers\OrderController:getBox');

$app->get('/unicene-tablice', 'App\Controllers\DestroyedPlateController:indexTablice');
$app->get('/ponarejene-tablice', 'App\Controllers\DestroyedPlateController:indexPonaredki');

$app->get('/paketi/{id}/{packsn}', 'App\Controllers\DestroyedPlateController:indexPack');
$app->get('/paketi', 'App\Controllers\DestroyedPlateController:indexPacks');

$app->get('/getDestroyedPackages', 'App\Controllers\DestroyedPlateController:GetDestroyedPackages');
$app->post('/exportPacksToExcel', 'App\Controllers\DestroyedPlateController:ExportSelectedDestroyedPackages');
$app->get('/getDestroyedPackageDetails', 'App\Controllers\DestroyedPlateController:GetDestroyedPackageDetails'); 
$app->get('/getDestroyedPlateImage/{id}', 'App\Controllers\DestroyedPlateController:getDestroyedPlateImage');
$app->get('/getDestroyedPlates', 'App\Controllers\DestroyedPlateController:getDestroyedPlates');
$app->get('/GetFakeDestroyedPlates', 'App\Controllers\DestroyedPlateController:getFakeDestroyedPlates');
$app->get('/getSearchFakeDestroyedPlates', 'App\Controllers\DestroyedPlateController:getSearchFakeDestroyedPlates');
$app->post('/sendDestroyedPlates', 'App\Controllers\DestroyedPlateController:sendDestroyedPlates');



/**
 * Account managment -----------------------------------------------------------//
 */
$app->get('/prijava', 'App\Controllers\AccountController:loginForm');
$app->get('/pozabljeno-geslo', 'App\Controllers\AccountController:forgottenPasswordForm');
$app->get('/vpis-novega-gesla', 'App\Controllers\AccountController:enterNewPasswordForm');
$app->get('/vpis-novega-gesla-uspesno', 'App\Controllers\AccountController:enterPasswordSuccess');

$app->get('/menjava-gesla','App\Controllers\AccountController:changePasswordForm');
$app->get('/menjava-gesla-uspesno','App\Controllers\AccountController:changePasswordSuccess');
$app->get('/odjava', 'App\Controllers\AccountController:logout');

$app->post('/prijava', 'App\Controllers\AccountController:login');
$app->post('/userclosewindow', 'App\Controllers\AccountController:userIsCloseWindow');
/* zamenjava gesla, ko si vpisan */
$app->post('/changepassword', 'App\Controllers\AccountController:changePassword');

/* zamenjava gesla, ko še nisi vpisan */
$app->post('/pozabljeno-geslo', 'App\Controllers\AccountController:emailValidateAndSend');
$app->post('/menjava-gesla-iz-notifikacije', 'App\Controllers\AccountController:changePasswordFromMailNotification');

/**
 * Rest call for webservis transfer
 */
$app->group('/wsapi', function () {
    $this->get('/getusers', 'App\Controllers\WebServiceController:getUsers');
	$this->get('/helloWorld', 'App\Controllers\WebServiceController:helloWorld');
	$this->post('/helloWorldPost', 'App\Controllers\WebServiceController:helloWorldPost');
    
    $this->post('/downloadconfirmation', 'App\Controllers\WebServiceController:downloadConfirm');
    

    //orders
    $this->post('/updateorders', 'App\Controllers\WebServiceController:updateOrders');
    $this->post('/updateorderdetails', 'App\Controllers\WebServiceController:updateOrderDetails');
    
    //customers
    $this->post('/updatecustomers', 'App\Controllers\WebServiceController:updateCustomers');
    $this->post('/updatecustomeruser', 'App\Controllers\WebServiceController:updateUser');
    $this->post('/deletecustomeruser', 'App\Controllers\WebServiceController:deleteUser');
    $this->post('/resetpassword', 'App\Controllers\WebServiceController:resetPassword');

    //dispatches
    $this->post('/updatedispatches', 'App\Controllers\WebServiceController:updateDispatches');
    $this->post('/updatedispatchdetails', 'App\Controllers\WebServiceController:updateDispatchDetails');

    //boxes
    $this->post('/updateboxes', 'App\Controllers\WebServiceController:updateBoxes');
    $this->post('/updateboxdetails', 'App\Controllers\WebServiceController:updateBoxDetails');
});