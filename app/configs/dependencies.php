<?php
/**
 * Set dependencies to Dependecy Injection container
 */

/**
 * Get instance of DI container
 */
$container = $app->getContainer();

/**
 * Set logger engine
 */
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

/**
 * Set view render engine
 */
// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);
    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    $view->addExtension(new APP\ViewExtensions\Twig_JSON());
    $view->addExtension(new APP\ViewExtensions\Twig_RenderImage());
    return $view;
};
/**
 * Register database connection privider
 */
$container['dbConn'] = function ($c) {
    $settings = $c->get('settings');
    $appEnviroment = $settings["app_development_mode"];
    $dbSettings = $settings["database"][$appEnviroment];
    $dbConn = new APP\Databases\DbConnectionProvider($dbSettings, $c->get('logger'));
    return $dbConn;    
};

/**
 * Set Cross-Site Request Forgery handler 
 */
$container['csrf'] = function ($c) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) {
        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });
    return $guard;
};

// Flash messages
$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages;
};

/*******************************************************************************
 * helpers for JSON - response request 
 ******************************************************************************/
$container['jsonRender'] = function($c){
    $view = new App\Helpers\JsonRenderer();

  return $view;
};

$container['jsonRequest'] = function ($c) {
  $jsonRequest = new App\Helpers\JsonRequest();

  return $jsonRequest;
};

/*******************************************************************************
 * handlers - exceprtion, response request 
 ******************************************************************************/
$container['notAllowedHandler'] = function ($c) {
  return function ($request, $response, $methods) use ($c) {

    $view = new App\Helpers\JsonRenderer();
    return $view->render($response, 405,
        ['error_code' => 'not_allowed', 'error_message' => 'Method must be one of: ' . implode(', ', $methods)]
    );

  };
};

$container['notFoundHandler'] = function ($c) {
  return function ($request, $response) use ($c) {
    $view = $c->get('view');
    $view->render($response, '404.twig', array());

    return $c['response'] 
            ->withStatus(404); 

    /*var_dump($response);

    $settings = $c->get('settings');
    $path = $settings['view']['template_path'];

    $view = $c->get('view');
    //var_dump($view);
    return $view->render($response, $path.'/account/account.accessdenied.twig', array());
    //$view = new App\Helpers\JsonRenderer();
*/
    //return $view->render($response, 404, ['error_code' => 'not_found', 'error_message' => 'Not Found']);
  };
};

$container['errorHandler'] = function ($c) {
  return function ($request, $response, $exception) use ($c) {

    //var_dump($exception);
    //$custommsg = substr(substr($exception->getMessage(), strlen("exception 'Exception' with message '")), strlen("' in "));
    $test = explode("\n", $exception->getMessage());
    $misc = str_replace("exception 'Exception' with message '", "", $test[0]);
    
    //echo $misc;
    $x = substr($misc, strpos($misc, "'"), strlen($misc) - strpos($misc, "'"));
    $custum_msg =  str_replace($x, "", $misc);

    $settings = $c->settings;
    $view = new App\Helpers\JsonRenderer();
    $logger = $c->logger;
    $logger->error($exception);

    $errorCode = 200;
    if (is_numeric($exception->getCode()) && $exception->getCode() > 300  && $exception->getCode() < 600) {
      $errorCode = $exception->getCode();
    }

    if ($settings['displayErrorDetails'] == true) {
      $data = [
          'status' => 'error',
          'message' => $custum_msg,
          'error_code' => $errorCode,
          'error_message' => $exception->getMessage(),          
          'file' => $exception->getFile(),
          'line' => $exception->getLine(),
          'trace' => explode("\n", $exception->getTraceAsString()),
      ];
    } else {
      $data = [
          'status' => 'error',
          'message' => $custum_msg,
          'error_code' => $errorCode,
          'error_message' => $exception->getMessage()
      ];
    }    

    return $view->render($response, $errorCode, $data);
  };
};

/**
 * Auth provider
 */
$container["authProvider"] = function ($c) use($app) {
    $sessions = new \App\Helpers\Session();
    $auth = new APP\ModelsSupport\LoginRepository($c->get('dbConn'), $sessions);
    return $auth;
};

/**
 * Mail sender
 */
$container["mailSender"] = function ($c) use($app) {
    $settings = $c->get('settings');
    $appEnviroment = $settings["app_development_mode"];
    $mailSettings = ($appEnviroment === true) ? $settings["mailSender"]["development"] : $settings["mailSender"][$appEnviroment];
    $mailSender = new App\Helpers\MailSender($mailSettings);
    return $mailSender;
};
$container["mailNotification"] = function ($c) use($app) {
   $mailSender = new App\Helpers\MailNotification($c->get('mailSender'), $c->get('logger'), $c->get('settings'));
    return $mailSender;
};

//-----------------------------------------------------------------------------//
// controller register
//-----------------------------------------------------------------------------//
$container["App\Controllers\HomeController"] = function ($c) use ($app) {
    $home = new \App\Controllers\HomeController($app);
    return $home;
};
$container["App\Controllers\AccountController"] = function ($c) use ($app) {
    $account = new \App\Controllers\AccountController($app);
    return $account;
};
$container["App\Controllers\WebServiceController"] = function ($c) use ($app) {
    $ws = new \App\Controllers\WebServiceController($c->get('dbConn'), $c->get('jsonRequest'), $c->get('jsonRender'));
    return $ws;
};
$container["App\Controllers\OrderController"] = function ($c) use ($app) {
    $orderCtrl = new \App\Controllers\OrderController($app);
    return $orderCtrl;
};
$container["App\Controllers\DestroyedPlateController"] = function ($c) use ($app) {
    $orderCtrl = new \App\Controllers\DestroyedPlateController($app);
    return $orderCtrl;
};