<?php
namespace App\ModelsSupport;

use App\Databases\DbConnectionProvider;
use App\Models\UserModel;
use App\Helpers\Session;
use App\Helpers\MailNotification;
use App\Helpers\GuidGenerator;
/**
 * Class for creating user entity and save to database
 *
 * @author b.pelko
 */
class UserFactory {

    public $errors;
    /**
     * @var Session
     */
    private $session;

    /**
     * @var DbConnectionProvider
     */
    private $dbConn;

    public function __construct(DbConnectionProvider $dbConn, Session $session) {
        
        $this->dbConn = $dbConn;
        $this->session = $session;
        $this->errors = array();
    }
    
    /**
     * User registration
     * @param array $postParams
     * @param MailNotification $mailNotification
     * @return array
     */
    public function createRegisteredUser($postParams, $mailNotification) {
        //var_dump($postParams);
        $captcha = $postParams["captcha"];
        $email = $postParams['email'];
        $password = $postParams["password"];
        $password2 = $postParams["password2"];
        
        //current captcha phase
        $currentCaptcha = $this->session->get('phrase');
        //echo $currentCaptcha;
        
        if ($captcha != $currentCaptcha) 
        {
            $this->errors[] = "slika se ne ujema z vpisano besedo";
        }  
        else 
        {
            $userModel = new UserModel($this->dbConn);
            $userModel->createRegisteredUser($email, $password, $password2);
            if ($userModel->isRegistrationValid()) 
            {
                $anySaveRegistrationError = $userModel->saveRegistration();
                if (count($anySaveRegistrationError) == 0) 
                {
                    $mailNotification->SendRegistrationNotification($email, $userModel->getId());
                }
                else 
                {
                    $this->errors = $userModel->getErrors();
                }                
            } 
            else 
            {
                $this->errors = $userModel->getErrors();
            }
        }   
                
        return (count($this->errors) > 0) ? $this->errors : array();
    }

    public function activateUser($args) {
        $errors = array();
        if(empty($args)) {
            $errors[] = "Vhodni podatki za aktivacijo uporabnika niso pravilni !";
        } else {
            $userid = $args['id'];
            if(GuidGenerator::validationGuid($userid)) {
                $userModel = new UserModel($this->dbConn);
                $userModel->activateUser($userid);
                $anyerrors = $userModel->getErrors();
                if (count($anyerrors) > 0) {
                    $errors = $userModel->getErrors();
                }
            } else {
                $errors[] = "Vhodni podatki za aktivacijo uporabnika niso pravilni !";
            } 
        }
        return $errors;
    }

    function changePasswordFromServis($postParams) {
        $usermodel = new UserModel($this->dbConn);
        if (empty($postParams['Id'])) {
            throw new \Exception("Manjka id uporabnika");
        }

        if (empty($postParams['PasswordPlain'])) {
            throw new \Exception("Manjka geslo, ki ga želite nastaviti");    
        }
        $usermodel->resetPasswordFromServis($postParams['Id'], $postParams['PasswordPlain']);        

    } 
    
    function createProfessionalUser($postParams) {
        $usermodel = new UserModel($this->dbConn);

        /*$FirstName = (isset($postParams['FirstName'])) ? $postParams['FirstName'] : '';
        $LastName = (isset($postParams['LastName'])) ? $postParams['LastName'] : '';
        $PhoneNumber = (isset($postParams['PhoneNumber'])) ? $postParams['PhoneNumber'] : '';
        $MobileNumber = (isset($postParams['MobileNumber'])) ? $postParams['MobileNumber'] : '';
        $Company = (isset($postParams['Company'])) ? $postParams['Company'] : '';
        $StreetName = (isset($postParams['StreetName'])) ? $postParams['StreetName'] : '';
        $PostNumber = (isset($postParams['PostNumber'])) ? $postParams['PostNumber'] : '';
        $PostName = (isset($postParams['PostName'])) ? $postParams['PostName'] : '';
        */
       
        $name = (isset($postParams['Name'])) ? $postParams['Name'] : '';
        $customerId = (isset($postParams['CustomerId'])) ? $postParams['CustomerId'] : '';
        // $canViewSubUnits = (isset($postParams['CanViewSubunitsData'])) ? $postParams['CanViewSubunitsData'] : false;
        // $canViewOrders = (isset($postParams['CanViewOrders'])) ? (($postParams['CanViewOrders'] === true) ? true : false) : false;
        // $canViewAllDestroyedPlates = (isset($postParams['CanViewAllDestroyedPlates'])) ? (($postParams['CanViewAllDestroyedPlates'] === true) ? true : false) : false;
        // $canViewAllFakeDestroyedPlates = (isset($postParams['CanViewAllFakeDestroyedPlates'])) ? (($postParams['CanViewAllFakeDestroyedPlates'] === true) ? true : false) : false;

        $canViewSubUnits = ($postParams['CanViewSubunitsData'] == true) ? 1 : 0;
        $canViewOrders = ($postParams['CanViewOrders'] == true) ? 1 : 0;

        $canViewAllDestroyedPlates = ($postParams['CanViewAllDestroyedPlates'] == true) ? 1 : 0;
        $canViewAllFakeDestroyedPlates = ($postParams['CanViewAllFakeDestroyedPlates'] == true) ? 1 : 0;

        
       
        $usermodel->setAdditionalInfo($name, $customerId, $canViewSubUnits, $canViewOrders, $canViewAllDestroyedPlates, $canViewAllFakeDestroyedPlates);
        
        $active = ($postParams['IsActive'] === true) ? 1 : 0;
        $isNew = (isset($postParams['IsNew'])) ? (($postParams['IsNew'] === true) ? 1 : 0) : 0;


        $usermodel->changeActivity($active);
        $usermodel->createProfiUser($postParams['Id'], $postParams['Email'], $isNew, $postParams['PasswordPlain']);
    }

    function checkChangePasswordForm($postParams, $authData) {
        $oldpassword = $postParams['oldpassword'];
        $password = $postParams['password'];
        $password2 = $postParams['password2'];
        // echo $oldpassword;
        
        $userModel = new UserModel($this->dbConn);
        $processErrors = $userModel->setChangePassword($authData['userid'], $oldpassword, $password, $password2);
        return $processErrors;
    }

    function checkChangePasswordFromMailNotification($postParams) {
        $password = $postParams['password'];
        $password2 = $postParams['password2'];
        $id = $postParams['id'];
        
        $userModel = new UserModel($this->dbConn);
        $processErrors = $userModel->setChangePasswordFromMailNotification($id, $password, $password2);
        return $processErrors;
    }

    function validateEmail($email, $blobString) {
        $errors = array();

        if (empty($email)) {
            $errors[] = "E-mail je prazen !";
            return $errors;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Pravilno vnesite elektronski naslov.";
            return $errors;
        }

        $userModel = new UserModel($this->dbConn);
        $processEmail = $userModel->setKeyAndUpdate($email, $blobString);
        return $processEmail;
    }

     /**
     * Generate a random string
     */ 

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function erasePassKeyFromUser($postParams) {
        $id = $postParams['id'];
        $email = $postParams['username'];
        
        $userModel = new UserModel($this->dbConn);
        $erasePassKey = $userModel->setErasePassKey($email, $id);
        return $erasePassKey;
    }

     /**
     * Send Mail Notification that sends the link with parameters for changing password
     */ 

    function deleteCrmUser($postParams) {
        $usermodel = new UserModel($this->dbConn);

        $userid = (isset($postParams['Id'])) ? $postParams['Id'] : '';

        if (empty($userid)) {
            throw new \Exception('Manjka id uporabnika');
        }

        $usermodel->deleteUser($userid);
    }
}