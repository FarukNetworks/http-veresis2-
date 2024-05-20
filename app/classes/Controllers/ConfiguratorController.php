<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use App\Databases\DbConnectionProvider;



/**
 * ConfiguratorController
 *
 * @author m.strlekar
 */
class ConfiguratorController {

    /**
     * @var Twig
     */
    private $view;

    private $authProvider;

    private $userRepository;

    private $dbConnProv;
        
    private $session;
    /**
     * Constructor
     * @param DbConnectionProvider $dbConnProv
     * @param Twig $view
     */
    public function __construct($app) {
        
        $container = $app->getContainer();
        $this->dbConnProv = $container->get('dbConn');
        $this->view = $container->get('view');
        $this->authProvider = $app->getContainer()->get('authProvider');
        $this->session = new \App\Helpers\Session();
        $this->userRepository = new \App\ModelsSupport\UserRepository($this->dbConnProv);

    }
    
    /**
     * Render Konfigurator page
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function indexAction(RequestInterface $request, ResponseInterface $response) {
        $authData = $this->authProvider->isUserLoggedIn();
        if (empty($authData)) {
            return $this->view->render($response, 'account/account.loginform.twig',array());
        }

        $userData = $this->userRepository->getUserById($authData['userid']);
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }

        $data = [
                'authdata' => $authData,
                // can view all sidebar items
                'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"]),
                'activepage' => 'konfigurator'
            ];
        
        return $this->view->render($response, 'configurator/configurator.twig', $data);
    }
}



