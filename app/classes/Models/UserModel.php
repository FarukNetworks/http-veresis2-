<?php
namespace App\Models;

use App\Databases\DbConnectionProvider;

/**
 * Description of UserModel
 *
 * @author b.pelko
 */
class UserModel extends BaseModel {

    /**
     * @var DbConnectionProvider
     */
    private $dbConn;
    private $logger;
    public $password_salt = "72f32916116c9da5ce6c385dc307d968298ce8c508b4b670fabc6521984081a10b32480a618d9351e68d8d0fef8f8ceabf42717ec285b59c7eb342cef12f0653";
    private $dbtable = 'user';
    private $errors = array();
    
    private $username;
    private $password;
    private $email;
    private $first_name;
    private $last_name;
    private $full_name;
    private $telephone;
    private $mobile;
    private $company;
    private $street_name;
    private $post_number;
    private $post_name;
    private $is_active;

    private $customerId;
    private $canViewSubUnits;
    private $canViewOrders;
    private $canViewAllDestroyedPlates;
    private $canViewAllFakeDestroyedPlates;
    
    /**
     * 1 - visitor, 2- registerdd user, 3 - prof. user
     * @var int
     */
    private $user_type;
    private $downloaded;
    private $downloaded_at;
    private $downloaded_by;

    private $pass_key;


    public function __construct(DbConnectionProvider $dbConn) {
        parent::__construct();
        $this->dbConn = $dbConn;
        $this->logger = $dbConn->getLogger();
    }
    
    public function createRegisteredUser($email, $password, $password2) {
        $this->setId();
        $this->setUsername($email);
        $this->validatePassword($password, $password2);
        $this->setPassword($password);
        $this->user_type = 1;
        $this->setCreatedInfo($email);
        $this->is_active = 1;
    }        
    
    public function getErrors() {
        return $this->errors;
    }
    public function isRegistrationValid() {
        return (count($this->errors) > 0) ? false : true;
    }

            
    private function setUsername($email) {
        if (empty($email)) {
            $this->errors[] = "Elektronski naslov manjka";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Pravilno vnesite elektronski naslov";
        }

        if ($this->dbConn->checkExists($this->getTableName(), array('email' => $email))) {
            $this->errors[] = "Uporabnik z tem elektronskim naslovom že obstaja";
        }

        $this->username = $email;
        $this->email = $email;
        
        if ($this->user_type == 1) {
            $this->full_name = $email;
        }        
    }
    
    private function validatePassword($password, $password2) {
        if (empty($password)) {
            $this->errors[] = "Geslo manjka";
        }        
        if (!preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/", $password)) {
            $this->errors[] = "Geslo mora biti dolgo minimalno 6 znakov. Vsebovati mora: 1 veliko črko, 1 majhno črko in 1 številko";
        }        
        if (empty($password2)) {
            $this->errors[] = "Ponovno vnesite geslo v polje ponovi geslo";
        }        
        if (trim($password) !== trim($password2)) {
            $this->errors[] = "Gesli se ne ujemata";
        }
    }
    
    private function setPassword($password) {
        $this->password = hash('sha512', $password.$this->password_salt);
    }
    
    public function checkPassword($passFromDb, $passwordFromPost) {
        $is_password_correct = ($passFromDb == hash('sha512', $passwordFromPost.$this->password_salt)) ? true : false;
        return $is_password_correct;
    } 
    
    public function setAdditionalInfo($name, $customerId, $canViewSubUnits, $canViewOrders, $canViewAllDestroyedPlates, $canViewAllFakeDestroyedPlates) {
        $this->full_name = $name;
        $this->customerId = $customerId;
        $this->canViewSubUnits = $canViewSubUnits;
        $this->canViewOrders = $canViewOrders;
        $this->canViewAllDestroyedPlates = $canViewAllDestroyedPlates;
        $this->canViewAllFakeDestroyedPlates = $canViewAllFakeDestroyedPlates;
        /*$this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->full_name = $first_name.' '.$last_name;
        $this->telephone = $telephone;
        $this->mobile = $mobile;
        $this->company = $company;
        $this->street_name = $street_name;
        $this->post_number = $post_number;
        $this->post_name = $post_name;*/
    }
    
    public function changeActivity($active) {
        $this->is_active = $active;
    }
    
    public function setDownloadedInfo() {
        $this->downloaded = true;
        $this->downloaded_at = date("Y-m-d H:i:s");
        $this->downloaded_by = 'TSMService';        
    }

    public function setChangePassword($userid, $oldpassword, $password, $password2) {
        $errors = array();
        if (empty($userid)) {
            $errors[] = 'Uporabnik ni bil prijavljen';
            return $errors; 
        }
        if (empty($oldpassword)) {
            $errors[] = 'Obstoječe geslo je prazno !';
            return $errors; 
        }

        // echo $password;

        $passFromDb = $this->dbConn->fetchSingleRow($this->getTableName(), 'id', $userid);
        if (!$passFromDb) {
            $errors[] = 'Uporabnik ne obstaja';
            return $errors;
        }

        // var_dump($passFromDb);

        $isOldPasswordCorrect = $this->checkPassword($passFromDb->password, $oldpassword);

        if(!$isOldPasswordCorrect) {
            $errors[] = 'Obstoječe geslo ni pravilno.';
            return $errors;
        }

        $this->validatePassword($password, $password2);
        //print_r($this->errors);
        if (count($this->errors) > 0) {
            return $this->errors;
        }

        $this->setPassword($password);
        $this->savePasswordChange($userid, $passFromDb);
        return $errors;
    }

    public function setChangePasswordFrom($userid, $oldpassword, $password, $password2) {
        $errors = array();
        if (empty($userid)) {
            $errors[] = 'Uporabnik ni bil prijavljen';
            return $errors; 
        }
        if (empty($oldpassword)) {
            $errors[] = 'Obstoječe geslo je prazno !';
            return $errors; 
        }

        $passFromDb = $this->dbConn->fetchSingleRow($this->getTableName(), 'id', $userid);
        if (!$passFromDb) {
            $errors[] = 'Uporabnik ne obstaja';
            return $errors;
        }
        //var_dump($passFromDb);
        if (!$this->checkPassword($passFromDb->password, $oldpassword)) {
            $errors[] = 'Vaše obstoječe geslo je napačno. Ponovno ga vnesite !';
            //print_r($errors);
            return $errors;    
        }

        $this->validatePassword($password, $password2);
        //print_r($this->errors);
        if (count($this->errors) > 0) {
            return $this->errors;
        }

        $this->setPassword($password);
        $this->savePasswordChange($userid, $passFromDb);
        return $errors;
    }

    public function setChangePasswordFromMailNotification($userid, $password, $password2) {
        $errors = array();
        if (empty($userid)) {
            $errors[] = 'Prišlo je do napake. Prosimo, ponovno vpišite vaš e-naslov v prejšnem koraku.';
            return $errors; 
        }

        $passFromDb = $this->dbConn->fetchSingleRow($this->getTableName(), 'id', $userid);
        if (!$passFromDb) {
            $errors[] = 'Uporabnik ne obstaja. Prosimo, ponovno vpišite vaš e-naslov v prejšnem koraku.';
            return $errors;
        }

        $this->validatePassword($password, $password2);
        //print_r($this->errors);
        if (count($this->errors) > 0) {
            return $this->errors;
        }

        $this->setPassword($password);
        $this->savePasswordChange($userid, $passFromDb);
        return $errors;
    }

    public function setKeyAndUpdate($email, $blobString) {

        $passFromDb = $this->dbConn->fetchSingleRow($this->getTableName(), 'email', $email);
        // var_dump($passFromDb);
        $idFromUser = $passFromDb->id;
        if (!$passFromDb) {
            $errors[] = 'Uporabnik s tem e-naslovom ne obstaja.';
            return $errors;
        }

        $update = [
            'updated_at' => date("Y-m-d H:i:s"), 
            'updated_by' => $email,
            'pass_key' => $blobString
        ];
 
        $this->dbConn->update($this->getTableName(), $update, 'id', $idFromUser);
        $this->dbConn->saveActivityChangeLog("Uporabnik z mailom: {$email} je bil poslan mail za zamenjavo gesla.", $id, $id, "", "user", 0, 0);
    }

    public function setErasePassKey($email, $id) {

        $update = [
            'pass_key' => null
        ];
 
        $this->dbConn->update($this->getTableName(), $update, 'id', $id);
        $this->dbConn->saveActivityChangeLog("Uporabnik z mailom: {$email} je uspešno nastavil novo geslo, zbrisalo se je tudi polje pass_key.", $id, $id, "", "user", 0, 0);
    }

   
    /**
     * Reset password web servis
     */ 
    public function resetPasswordFromServis($id, $passwordToBeStored) {
        try
        {
            if (!preg_match_all("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/", $passwordToBeStored)) {
                throw new \Exception("Geslo, ki ga želite nastaviti ni pravilne oblike: Geslo mora biti dolgo minimalno 6 znakov. Vsebovati mora: 1 veliko črko, 1 majhno črko in 1 številko");
            }
            $this->setPassword($passwordToBeStored);
            $this->setUpdatedInfo('LppmServis');
           
            $update = [
                'password' => $this->password,
                'updated_at' => $this->updated_at, 
                'updated_by' => $this->updated_by
            ];

            if ($this->dbConn->checkExists($this->getTableName(), array('id' => $id))) {
                $this->dbConn->update($this->getTableName(), $update, 'id', $id);
                $this->dbConn->saveActivityChangeLog("Uporabniku id: {$id} je bilo spremenjeno geslo", $id, $id, "", "user", 0, 0);
            } else {
                throw new \Exception("Uporabnik v VeresisCrm z id: {$id} ne obstaja !");
            }
        }
        catch(\PDOException $pdoex)
        {
            $this->logger->error($pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        }
        catch(\Exception $ex)
        {
            $this->logger->error($ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }

    }

    public function createProfiUser($id, $email, $isNew, $passwordToBeStored = null) 
    {
        try
        {
            if (empty($id)) {
                    throw new \Exception("Manjka id uporabnika");                    
            }
            if ($this->dbConn->checkExists($this->getTableName(), array('id' => $id))) 
            {
                if (empty($email)) {
                    throw new \Exception("Manjka email uporabnika");
                }

                $user = $this->dbConn->fetchSingleRow($this->getTableName(), 'id', $id);
                //print_r($user);
                if (!empty($user)) {
                    //print_r($user);
                    if ($user->email != $email) {
                        if ($this->dbConn->checkExists($this->getTableName(), array('email' => $email))) {
                            throw new \Exception("Email: {$email} ima že eden od uporabnikov");
                        }
                    }
                } else {
                    throw new \Exception("Uporabnik v VeresisCrm z id: {$id} ne obstaja !");
                }                

                $update = [
                    'username' => $email,
                    'email' => $email,
                    'full_name' => $this->full_name,
                    'customerId' => $this->customerId,
                    'canViewSubUnits' => $this->canViewSubUnits,
                    'canViewOrders' => $this->canViewOrders,
                    'canViewAllDestroyedPlates' => $this->canViewAllDestroyedPlates,
                    'canViewAllFakeDestroyedPlates' => $this->canViewAllFakeDestroyedPlates,
                    'is_active' => $this->is_active,
                    'user_type' => 3,
                    'updated_at' => date("Y-m-d H:i:s"),
                    'updated_by' => 'LppmService'
                ];

                //print_r($update);
            
                $this->dbConn->update($this->getTableName(), $update, 'id', $id);                    
            } 
            else 
            {
                if (empty($id)) {
                    throw new \Exception("Manjka id uporabnika");                    
                }
                if (empty($email)) {
                    throw new \Exception("Manjka email uporabnika");
                }
                if ($isNew) {
                    if (!preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/", $passwordToBeStored)) {
                        throw new \Exception("Geslo, ki ga želite nastaviti ni pravilne oblike: Geslo mora biti dolgo minimalno 6 znakov. Vsebovati mora: 1 veliko črko, 1 majhno črko in 1 številko");
                    }    
                } else {
                    throw new \Exception("Uporabnik v VeresisCrm z id: {$id} ne obstaja !");
                }

                
                if ($this->dbConn->checkExists($this->getTableName(), array('email' => $email))) {
                    throw new \Exception("Email uporabnika z id: {$id} že obstaja");
                } 
                
                $this->setPassword($passwordToBeStored);                

                $insert = [
                    'id' => $id,
                    'username' => $email,
                    'password' => $this->password,
                    'email' => $email,
                    'full_name' => $this->full_name,
                    'customerId' => $this->customerId,
                    'canViewSubUnits' => $this->canViewSubUnits,
                    'canViewOrders' => $this->canViewOrders,
                    'canViewAllDestroyedPlates' => $this->canViewAllDestroyedPlates,
                    'canViewAllFakeDestroyedPlates' => $this->canViewAllFakeDestroyedPlates,
                    'is_active' => $this->is_active,
                    'user_type' => 3,
                    'created_at' => date("Y-m-d H:i:s"),
                    'created_by' => 'LppmService'
                ];
            
                $this->dbConn->insert($this->getTableName(), $insert);
            }            
                        
        }
        catch(\PDOException $pdoex)
        {
            $this->logger->error($pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        }
        catch(\Exception $ex)
        {
            $this->logger->error($ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }    
    }
    
    public function saveRegistration() {
        try
        {
            $insert = [
                'id' => $this->getId(),
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
                'user_type' => $this->user_type,
                'created_at' => $this->created_at,
                'created_by' => $this->created_by,
                'is_active' => $this->is_active
            ];
            
            if ($this->dbConn->checkExists($this->getTableName(), array('email' => $this->email))) {
                $this->errors[] = "Uporabnik z tem elektronskim naslovom: {$this->email} že obstaja";
                $this->dbConn->saveActivityChangeLog("Uporabnik že obstaja !", $this->email);
            } else {
                $this->dbConn->insert($this->getTableName(), $insert);
                $this->dbConn->saveActivityChangeLog("Uporabnik z id: {$this->getId()} se je uspešno registriral", "", "", "", "user", "", 1);
            }
            return $this->errors;
        }
        catch (\PDOException $pdoex) {
            $this->logger->addError($pdoex->getMessage()."\n".$pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        } 
        catch (\Exception $ex) {
            $this->logger->addError($ex->getMessage()."\n".$ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }
    
    public function activateUser($userid) {
        try
        {
            $user = $this->dbConn->fetchSingleRow($this->getTableName(), 'id', $userid);
            if (empty($user)) {
                $this->errors[] = "Uporabnik ne obstaja";
                $this->dbConn->saveActivityChangeLog("Uporabnik z id: {$userid} ne obstaja", $userid, $userid, "", "user");
            } else {
                if ($user->user_type == 2 || $user->user_type == 3) {
                    $this->errors[] = "Uporabnik je že aktiviran.";
                } else {
                    $this->setUpdatedInfo($user->username);
                    $data = ['user_type' => 2, 'updated_at' => $this->updated_at, 'updated_by' => $this->updated_by];
                    $this->dbConn->update($this->getTableName(), $data, 'id', $userid);
                    $this->dbConn->saveActivityChangeLog("Uporabnik z id: {$userid} je bil spremenjen iz obiskovalca v registriranega uporabnika", $userid, $userid, "", "user", 1, 2);                    
                }
            }
        }
        catch (\PDOException $pdoex) {
            $this->logger->addError($pdoex->getMessage()."\n".$pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        } 
        catch (\Exception $ex) {
            $this->logger->addError($ex->getMessage()."\n".$ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }

    function savePasswordChange($userid, $existUser) {
        try
        {
            $this->setUpdatedInfo($existUser->username);
            $arrayUpdate = [
                'password' => $this->password,
                'updated_at' => $this->updated_at, 
                'updated_by' => $this->updated_by
            ];

            // var_dump($arrayUpdate);

            //print_r($arrayUpdate);
            $this->dbConn->update($this->getTableName(), $arrayUpdate, 'id', $userid);
            $this->dbConn->saveActivityChangeLog("Uporabnik z mailom: {$existUser->username} si je spremenil geslo", $userid, $userid, "", "user", 0, 0);
            $this->errors = array();
        }
        catch (\PDOException $pdoex) {
            $this->logger->addError($pdoex->getMessage()."\n".$pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        } 
        catch (\Exception $ex) {
            $this->logger->addError($ex->getMessage()."\n".$ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }

    function deleteUser($userid) {
        try
        {
            if ($this->dbConn->checkExists($this->getTableName(), array('id' => $userid))) {
                $this->dbConn->delete($this->getTableName(), 'id', $userid);
                $this->dbConn->saveActivityChangeLog("Uporabnik je bil zbrisan iz baze", $userid, $userid, "", "user", 0, 0);
            } else {
                throw new \Exception('Uporabnik ne obstaja');
            }           
            
        }
        catch (\PDOException $pdoex) {
            $this->logger->addError($pdoex->getMessage()."\n".$pdoex->getTraceAsString());
            // throw new \PDOException($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        } 
        catch (\Exception $ex) {
            $this->logger->addError($ex->getMessage()."\n".$ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }   
    
    
    private function getTableName() {
        return $this->dbConn->getTablePrefix().$this->dbtable;
    }
}
