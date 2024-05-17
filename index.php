<?php
/**
 * Set constant for prevent direct script access
 * If this constant not set, direct access, include script not allowed 
 */
/*$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}*/

define('XXXBASE', 1);

require __DIR__ . '/vendor/autoload.php';


require __DIR__. '/app/classes/Infrastructure/autoload.php';


date_default_timezone_set('Europe/Ljubljana');


/**
 * App is development
 */ 
// define("APPDEVELOPMENT", 'kigproduction');
define("APPDEVELOPMENT", 'sibit');

define("APPLANG", 'sl-SI');

/**
 * APP work on http ot https
 */ 
// was https
APP\Helpers\Uri::$appworkon = 'http';

/**
 * Set application base url
 */
define("APPBASEURL", APP\Helpers\Uri::baseUrl());

/**
 * set for cookie managment
 */
define("COOKIEDOMAIN", $_SERVER['HTTP_HOST']);

/**
 * App basepath
 */
define("APPBASEPATH", __DIR__);

/**
 * Set assets directory url for css,js, images files
 */
define("APPASSETS", APP\Helpers\Uri::baseUrl() . 'app/assets');

/**
 * Session start is need for csrf (Cross Site Request Forgery)
 * @see app/configs/dependencies.php - csrf registration
 */
session_start();

// Instantiate the app
$settings = require __DIR__ . '/app/configs/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/app/configs/dependencies.php';

// Register middleware
require __DIR__ . '/app/configs/middleware.php';

// Register routes
require __DIR__ . '/app/configs/routes.php';

// Run app
$app->run();