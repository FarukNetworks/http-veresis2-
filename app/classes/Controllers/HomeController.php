<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use App\Databases\DbConnectionProvider;

/**
 * HomeController
 *
 * @author b.pelko
 */
class HomeController {

    /**
     * @var Twig
     */
    private $view;

    private $authProvider;
        
    private $session;
    /**
     * Constructor
     * @param DbConnectionProvider $dbConnProv
     * @param Twig $view
     */
    public function __construct($app) {
        
        $container = $app->getContainer();
        $this->view = $container->get('view');
        $this->authProvider = $app->getContainer()->get('authProvider');
        $this->session = new \App\Helpers\Session();
    }
    
    /**
     * Render Home page
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function indexAction(RequestInterface $request, ResponseInterface $response) {
        $authData = $this->authProvider->isUserLoggedIn();
        if (empty($authData)) {
            return $this->view->render($response, 'account/account.loginform.twig',array());
        }

        $data = [
                'authdata' => $authData
            ];
        
        return $this->view->render($response, 'home.twig',$data);
    }
}
