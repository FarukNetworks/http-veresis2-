<?php
namespace App\Controllers;

/**
 * Description of AccountController
 *
 * @author b.pelko
 */

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Databases\DbConnectionProvider;
use Slim\Views\Twig;
use Slim\Csrf\Guard;
use Gregwar\Captcha\CaptchaBuilder;
use App\Helpers\Session;


class AccountController {

    private $templatePrefix = "account/account.";
    
    /**
     * @var Guard
     */
    private $csrf;
    
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var DbConnectionProvider
     */
    private $dbConnProv;
    
    private $session;
    
    /**
     * Main notification
     * @var MailNotification
     */
    private $mailNotification;


    private $userRepository;

    /**
     * Constructor
     * @param $app
     */
    public function __construct($app) {
        
        $container = $app->getContainer();
        $this->dbConnProv = $container->get('dbConn');
        $this->view = $container->get('view');
        $this->csrf = $container->get('csrf');
        $this->mailNotification = $container->get('mailNotification');
        $this->authProvider = $container->get('authProvider');
        $this->session  = new Session;
        $this->userRepository = new \App\ModelsSupport\UserRepository($this->dbConnProv);
    }

    private function correctRedirectAfterLogin() {

    }

    function isUserAuthenticateForRequest() {
        $authData = $this->authProvider->isUserLoggedIn();
        return $authData;
    } 
    
    /**
     * Get Cross Site Request Forgery Tokens key/value pairs. Valus generated in CSRF midellware
     * @param Request $request
     * @return array
     */
    private function getCsrfKeys(Request $request) {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        
        $tokenArray = ['name' => $name, 'value' => $value];
        return $tokenArray;
    }
    
    private function generateCaptcha() {
        $this->session->delete('phrase');         
        $builder = new CaptchaBuilder;
        $this->session->set('phrase', $builder->getPhrase());
        
        $builder->setBackgroundColor($r = 236, $g = 236, $b = 236)
        ->setIgnoreAllEffects($ignoreAllEffects = true)
        ->setDistortion($distortion = true)
        ->setMaxAngle($maxAngle = 0)
        ->setMaxOffset($maxOffset = 0)
        ->setTextColor($r = 0, $g = 104, $b = 170)
        ->build();
        return $builder;        
    }
    
    function getCaptchaImage() {
        $builder = $this->generateCaptcha();        
        return '<img src="'.$builder->inline().'" />';
    }
           
    public function registrationForm(Request $request, Response $response) {
        $csrfTokens = $this->getCsrfKeys($request);
        //$this->generateCaptcha();                
        $data = [
            'csrf' => $csrfTokens,
            'captchaBuilder' => $this->generateCaptcha()
        ];
        return $this->view->render($response, $this->getTemplateName('registrationform'), $data);
    }
    
    public function createUser(Request $request, Response $response) {
        $postParams = $request->getParsedBody();
        $userFactory = new \APP\ModelsSupport\UserFactory($this->dbConnProv, $this->session);
        $anyRegistrationErrors = $userFactory->createRegisteredUser($postParams, $this->mailNotification);
        if (count($anyRegistrationErrors) == 0) {
            return $response->withRedirect('registracija-uspela');
        } else {
            $data = [
                'captchaBuilder' => $this->generateCaptcha(), 
                'errors' => $anyRegistrationErrors,
                'flash' => '',
                'request' => $request,
                'postParams' => $postParams
            ];
            //$this->generateCaptcha();
            $this->view->render($response, $this->getTemplateName('registrationform'), $data);
        }       
    }
    
    public function registrationSuccess(Request $request, Response $response) {
        return $this->view->render($response, $this->getTemplateName('registrationsuccess'), array());
    }     
    
    public function activateUser(Request $request, Response $response, $args) {
        $userFactory = new \APP\ModelsSupport\UserFactory($this->dbConnProv, $this->session);
        $anyUserActivationErrors = $userFactory->activateUser($args);
        $data = [
            'errors' => $anyUserActivationErrors
        ];
        
        return (count($anyUserActivationErrors) == 0) ?
            $response->withRedirect(APPBASEURL.'prijava') : 
            $this->view->render($response, $this->getTemplateName('useractivation'), $data);
    }

    function checkIfUserCanViewOrders($userid) {
        $userData = $this->userRepository->getUserById($userid);    
        if($userData["canViewOrders"] == "all") {
            throw new \Exception('Dostop zavrnjen!');
        }
    }
    
    public function loginForm(Request $request, Response $response) {
        // $authData = $this->isUserAuthenticateForRequest();
        // if (!empty($authData)) {

        //     return $response->withRedirect(APPBASEURL.'narocila');
        // }
        $data = [
            // 'authdata' => $authData,
            'retUrl' => $request->getQueryParams()['retUrl']
        ];
        return $this->view->render($response, $this->getTemplateName('loginform'), $data);
        
    }
    
    public function isUserAuthenticate() {
        $loginRepo = new \APP\ModelsSupport\LoginRepository($this->dbConnProv, $this->session);
        $loginRepo->isUserLoggedIn();
    }
    
    public function login(Request $request, Response $response) {
        $postParams = $request->getParsedBody();
        // var_dump($postParams);
        $formValidErrors = \APP\ModelsSupport\LoginRepository::validateLoginForm($postParams);
        if (count($formValidErrors) == 0) 
        {
            $loginRepo = new \APP\ModelsSupport\LoginRepository($this->dbConnProv, $this->session);
            $hasPassword = $loginRepo->doUserHavePassword($postParams);
            if($hasPassword) {
                $data = ['flash' => 'Vaše geslo je poteklo. Prosimo vnesite vaš e-naslov za nastavitev novega gesla.'];
                return $this->view->render($response, 'account/account.forgottenpasswordform.twig', $data);
            }
            $loginRepo->setLoginData($postParams);
            if ($loginRepo->loginProcess()) 
            {
                return $response->withRedirect(APPBASEURL);
                $toUrl = $postParams['retUrl'];
                if (empty($toUrl)){
                    return $response->withRedirect(APPBASEURL.'narocila');
                }
                else
                {
                    return $response->withRedirect($toUrl);
                }
            }
            else
            {
                $data = ['errors' => $loginRepo->errors, 'flash' =>'', 'retUrl' => $postParams['retUrl']];
                return $this->view->render($response, $this->getTemplateName('loginform'), $data);
            }
        } 
        else 
        {
            $data = ['errors' => $formValidErrors, 'flash' =>''];
            return $this->view->render($response, $this->getTemplateName('loginform'), $data);
        }
    }
    
    public function logout(Request $request, Response $response) {
        
        $loginRepo = new \APP\ModelsSupport\LoginRepository($this->dbConnProv, $this->session);
        $loginRepo->logout();
        return $response->withRedirect(APPBASEURL);
    }
        
    public function updateUser() {
        
    }

    private function getTemplateName($name) {
        return $this->templatePrefix.$name.".twig";
    }
    
    function userIsCloseWindow(Request $request, Response $response) {
        $loginRepo = new \APP\ModelsSupport\LoginRepository($this->dbConnProv, $this->session);
        $loginRepo->setUserCloseWindow();
    }

    function changePasswordForm(Request $request, Response $response) {
        $authData = $this->isUserAuthenticateForRequest();
        if (empty($authData)) {

            $currentUrl = APPBASEURL.'menjava-gesla';
            return $response->withRedirect(APPBASEURL.'prijava?retUrl='.$currentUrl); 
        }

        $userData = $this->userRepository->getUserById($authData['userid']);    
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }

        $data = [
            'authdata' => $authData,
            'activepage' => 'none',
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];
        
        return $this->view->render($response, 'account/changepassword.twig', $data);
    }

    function changePasswordSuccess(Request $request, Response $response) {
        $authData = $this->isUserAuthenticateForRequest();
        if (empty($authData)) {

            $currentUrl = APPBASEURL.'menjava-gesla';
            return $response->withRedirect(APPBASEURL.'prijava');
        }

        $userData = $this->userRepository->getUserById($authData['userid']);    
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }

        $data = [
            'authdata' => $authData,
            'activepage' => 'none',
            'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
        ];
        return $this->view->render($response, 'account/changepassword.success.twig', $data);
    }

    function changepassword(Request $request, Response $response) {
        $authData = $this->isUserAuthenticateForRequest();
        if (empty($authData)) {
            throw new \Exception('Dostop zavrnjen. Morate biti prijavljeni !');
        }

        $userData = $this->userRepository->getUserById($authData['userid']);    
        if($userData["canViewOrders"] == "all") {
            return $response->withRedirect(APPBASEURL);
        }
        
        $postParams = $request->getParsedBody();
        $userFactory = new \App\ModelsSupport\UserFactory($this->dbConnProv, $this->session);
        $isFormValid = $userFactory->checkChangePasswordForm($postParams, $authData);
        if(count($isFormValid) > 0) {
            $data = [
                'errors' => $isFormValid, 
                'flash' =>'',
                'authdata' => $authData,
                'activepage' => 'none',
                'sidebaritems' => \App\Helpers\SidebarHelper::getSidebarItems($userData["canViewOrders"])
            ];

            return $this->view->render($response, 'account/changepassword.twig', $data);
        } else {
            return $response->withRedirect(APPBASEURL.'menjava-gesla-uspesno');
        }
    }

    public function forgottenPasswordForm(Request $request, Response $response) {
        return $this->view->render($response, $this->getTemplateName('forgottenpasswordform'));
    }  


    public function enterPasswordSuccess(Request $request, Response $response) {
        return $this->view->render($response, $this->getTemplateName('enterpasswordsuccess'));
    }      

    public function emailValidateAndSend(Request $request, Response $response) {
        $postParams = $request->getParsedBody();
        $email = $postParams['username'];
        $userFactory = new \App\ModelsSupport\UserFactory($this->dbConnProv, $this->session);
        $randomString = $userFactory->generateRandomString(20);
        $blobString = hash('ripemd160', $randomString);
        $isEmailValid = $userFactory->validateEmail($email, $blobString);

        if(count($isEmailValid) > 0) {
            $data = [
                'email' => $email,
                'errors' => $isEmailValid, 
                'flash' => ''
            ];

            return $this->view->render($response, 'account/account.forgottenpasswordform.twig', $data);
        } else {
            // var_dump($this->mailNotification);
            $queryString = [
                'username' => $email,
                'pass_key' => $blobString,
            ];
            $urlParams = http_build_query($queryString);
            $urlLink = APPBASEURL.'vpis-novega-gesla'.'?'.$urlParams;
            $sendMail = $this->mailNotification;
            $sendMail->SendChangePasswordNotification($email, $urlLink);

            $data = [ 
                'email' => $email,
                'flash' =>'Na vaš e-naslov je bila poslana potrditvena povezava.'
            ];

            return $this->view->render($response, 'account/account.forgottenpasswordform.twig', $data);

            // return $this->view->render($response->withRedirect($urlLink), 'account/account.enterpasswordform.twig', $data);
        }
    }

    public function enterNewPasswordForm(Request $request, Response $response) {
        $params = $request->getQueryParams();
        // var_dump($params);
        if(empty($params)) {
            return $response->withRedirect(APPBASEURL.'pozabljeno-geslo');
        }
        $user = $this->userRepository->getUserReferences($params);
        if(empty($user)) {

            $data = [
                'flash' => 'Vaša povezava za ponastavitev gesla je potekla. Prosimo, zahtevajte novo povezavo spodaj.'
            ];

            return $this->view->render($response, 'account/account.forgottenpasswordform.twig', $data);
        } else {

            // var_dump($user);
            $data = [
                'username' => $user[0]->username,
                'id' => $user[0]->id,
                'pass_key' => $user[0]->pass_key,
                'errors' => $params['errors']
            ];

            return $this->view->render($response, $this->getTemplateName('enterpasswordform'), $data);
        }
    }  

    public function changePasswordFromMailNotification(Request $request, Response $response) {

        $postParams = $request->getParsedBody();
        // var_dump($postParams);

        $userFactory = new \App\ModelsSupport\UserFactory($this->dbConnProv, $this->session);

        $isFormValid = $userFactory->checkChangePasswordFromMailNotification($postParams);

        if(count($isFormValid) > 0) {
            $params = [
                'username' => $postParams['username'],
                'pass_key' => $postParams['pass_key'],
                'errors' => $isFormValid
            ];
            $urlParams = http_build_query($params);
            $urlLink = APPBASEURL.'vpis-novega-gesla'.'?'.$urlParams;
            return $response->withRedirect($urlLink);
        } else {
            // $data = [ 
            //     'flash' =>'Geslo je bilo uspešno zamenjano.'
            // ];
            // return $this->view->render($response, 'account/account.loginform.twig', $data);
            $erasePassKey = $userFactory->erasePassKeyFromUser($postParams);
            return $response->withRedirect(APPBASEURL.'vpis-novega-gesla-uspesno');
            // return $response->withRedirect(APPBASEURL.'prijava');
        }
    }
}
