<?php
namespace App\ModelsSupport;

use App\Databases\DbConnectionProvider;
use Psr\Http\Message\RequestInterface as Request;
use App\Models\UserModel;
use App\Helpers\Session;
use \PDO;
use App\Helpers\CookieManager;

/**
 * Description of LoginRepository
 * http://www.dreamincode.net/forums/topic/326807-securing-login-forms-from-brute-force-attacks-using-queues/
 * @author b.pelko
 */
class LoginRepository {

    private $clientIp;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var DbConnectionProvider
     */
    private $dbConn;
    
    private $logger;
    
    public $errors;
    
    /**
     * @var int The number of milliseconds to sleep between login attempts.
     */
    const ATTEMPT_DELAY = 1000;

    /**
     * @var int The number of milliseconds before an unchecked attempt is
     *          considered dead.
     *
     */
    const ATTEMPT_EXPIRATION_TIMEOUT = 5000;

    /**
     * @var int Number of queued attempts allowed per user.
     */
    const MAX_PER_USER = 5;

    /**
     * @var int Number of queued attempts allowed overall.
     */
    const MAX_OVERALL = 30;
    
    private $maxInvalidLogins = 5;
    
    /**
     * The ID assigned to this attempt in the database.
     *
     * @var int
     */
    private $attemptID;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * After the login has been validated, this attribute will hold the
     * result. Subsequent calls to isValid will return this value, rather
     * that try to validate it again.
     *
     * @var bool
     */
    private $isLoginValid;

    /**
     * An open PDO instance.
     *
     * @var PDO
     */
    private $pdo;

    /**
     * Stores the statement used to check whether the attempt is ready to be processed.
     * As it may be used multiple times per attempt, it makes sense not to initialize
     * it each ready check.
     *
     * @var PDOStatement
     */
    private $readyCheckStatement;

    /**
     * The statement used to update the attempt entry in the database on
     * each isReady call.
     *
     * @var PDOStatement
     */
    private $checkUpdateStatement;
    
    /**
     * UserModel instance
     * @var UserModel
     */
    private $userModel;
    
    private $ipaddress;
    
    private $cookieManager;
    private $cookieName = 'lppmcrmUserId';
    
       
    /**
     * Constructor
     * @param DbConnectionProvider $dbConn
     * @param Session $session
     * @param array $postParams
     * @param string $clientIp
     * @throws Exception
     */
    function __construct(DbConnectionProvider $dbConn, Session $session) {
        
        $this->dbConn = $dbConn;
        $this->session = $session;
        $this->logger = $dbConn->getLogger();
              
        $this->pdo = $dbConn->openConnection();
        $this->userModel = new UserModel($dbConn);
        $this->cookieManager = new CookieManager($this->userModel->password_salt);
    }
        
    public static function validateLoginForm($postParams) {
        $errors = array();
        if (empty($postParams['username'])) {
            $errors[] = "Uporabniško ime je prazno !";
        }
        if (empty($postParams['password'])) {
            $errors[] = "Geslo je prazno";                    
        }        
        return $errors;
    }
    
    public function setLoginData($postParams) {
        $this->username = $postParams["username"];
        $this->password = $postParams["password"];
        
        if (!$this->isQueueSizeExceeded()) {
            $this->addToQueue();
        }
        else {
            throw new Exception("Queue size has been exceeded.", 503);
        }        
    }    
    
    public function loginProcess() {
        
        try {
            $this->whenReady(function($success) {
                $valid = $success ? true : false;
            });
            return $this->isLoginValid;
        }
        catch (\Exception $e) {
            if ($e->getCode() == 503) {
                $this->logger->error($e->getTraceAsString());
                header("HTTP/1.1 503 Service Unavailable");
                exit;
            }
            else if ($e->getCode() == 403) {
                $this->logger->error($e->getTraceAsString());
                header("HTTP/1.1 403 Forbidden");
                exit;
            }
            else {
                $this->logger->error($e->getTraceAsString());
                echo "Error: " . $e->getMessage();
            }            
        }
    }
    
    public function isUserLoggedIn() {
        $authData = array();
        /*if ($this->cookieManager->cookieExists($this->cookieName)) {
            $data = $this->cookieManager->getCookieValue($this->cookieName, true);
            $autharray = json_decode($data);
            $authData = [
                'userid' => $autharray->id,
                'user_type' => $autharray->user_type,
                'full_name' => (empty($autharray->full_name)) ? $autharray->email : $autharray->full_name,
                'email' => $autharray->email
            ];            
        }*/

        $data = $this->session->get($this->cookieName);
        if ($data) {
            $autharray = json_decode($data);
            $authData = [
                'userid' => $autharray->id,
                'full_name' => (empty($autharray->full_name)) ? $autharray->email : $autharray->full_name,
                'email' => $autharray->email
            ];
        }
        
        return $authData;
    }
    
    /**
     * Creates an entry for the attempt in the database, fetching the id
     * of it and storing it in the class. Note that no values need to
     * be entered in the database; the defaults for both columns are fine.
     */
    private function addToQueue()
    {
        echo $this->ipaddress;
        
        $sql = "INSERT INTO crm_login_attempt_queue (username)
                VALUES (?)";
        $stmt = $this->pdo->prepare($sql);
        try {
            $stmt->execute(array(
                $this->username
            ));
            $this->attemptID = (int)$this->pdo->lastInsertId();
        }
        catch (PDOException $e) {
            throw new Exception("IP address is already in queue.", 403);
        }
    }

    /**
     * Checks the queue size. Throws an exception if it has been exceeded. Otherwise it does nothing.
     *
     * @throws Exception
     * @return bool
     */
    private function isQueueSizeExceeded()
    {
        $sql = "SELECT
                    COUNT(*) AS overall,
                    COUNT(IF(username = ?, TRUE, NULL)) AS user
                FROM crm_login_attempt_queue
                WHERE last_checked > NOW() - INTERVAL ? MICROSECOND";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(
            $this->username,
            self::ATTEMPT_EXPIRATION_TIMEOUT * 1000
        ));

        $count = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$count) {
            throw new Exception("Failed to query queue size", 500);
        }

        return ($count->overall >= self::MAX_OVERALL || $count->user >= self::MAX_PER_USER);
    }

    /**
     * Checks if the login attempt is ready to be processed, and updates the
     * last_checked timestamp to keep the attempt alive.
     *
     * @return bool
     */
    private function isReady()
    {
        if (!$this->readyCheckStatement) {
            $sql = "SELECT id FROM crm_login_attempt_queue
                    WHERE
                        last_checked > NOW() - INTERVAL ? MICROSECOND AND
                        username = ?
                    ORDER BY id ASC
                    LIMIT 1";
            $this->readyCheckStatement = $this->pdo->prepare($sql);
        }
        $this->readyCheckStatement->execute(array(
            self::ATTEMPT_EXPIRATION_TIMEOUT * 1000,
            $this->username
        ));
        $result = (int)$this->readyCheckStatement->fetchColumn();

        if (!$this->checkUpdateStatement) {
            $sql = "UPDATE crm_login_attempt_queue
                    SET last_checked = CURRENT_TIMESTAMP
                    WHERE id = ? LIMIT 1";
            $this->checkUpdateStatement = $this->pdo->prepare($sql);
        }
        $this->checkUpdateStatement->execute(array($this->attemptID));

        return $result === $this->attemptID;
    }

    /**
     * Checks if the login attempt is valid. Note that this function will cause
     * the delay between attempts when first called. If called multiple times,
     * only the first call will do so.
     *
     * @return bool
     */
    public function isValid()
    {
        if ($this->isLoginValid === null) {
            $sql = "SELECT id, password, failed_login_count, locked, user_type, full_name, email FROM crm_user
                    WHERE email = ? 
                    and is_active=1 and user_type > 1";
            
            $results = $this->dbConn->fetch_custom($sql,array($this->username));
            
            if (count($results) > 1) {
                throw new \Exception("Ups, kako je mogoče, da obstajata dva uporabnika z enakim email naslovom !");                
            }
            if (count($results) > 0) {
                $user = $results[0];
                if ($user->locked == 1) {
                    $this->errors[] = "Prijava ni mogoča. Uporabniško ime je zaklenjeno";
                    $this->isLoginValid = false;
                } else {
                    $validLogin = $this->userModel->checkPassword($user->password, $this->password);
                    $this->saveLoginAttempToLog($validLogin, $user->id, true);
                    $this->setLockUserIfRequired($user, $validLogin);           
                    $this->isLoginValid = $validLogin;
                    
                    if ($validLogin) {
                        //$expire = time() + 86400; //24h
                        $userData = [
                            'id' => $user->id,
                            'user_type' => $user->user_type,
                            'full_name' => $user->full_name,
                            'email' => $user->email
                        ];
                        // $this->cookieManager->setCookie($this->cookieName, json_encode($userData), 'meblotsa', $expire,'/',COOKIEDOMAIN,0,true);
                        $this->session->set($this->cookieName, json_encode($userData));
                    }
                }     
                
                
            } else {
                $this->isLoginValid = false;
                $this->saveLoginAttempToLog(false);
            }            

            // Sleep at this point, to enforce a delay between login attempts.
            usleep(self::ATTEMPT_DELAY  * 1000);

            // Remove the login attempt from the queue, as well as any login
            // attempt that has timed out.
            $sql = "DELETE FROM crm_login_attempt_queue
                    WHERE
                        id = ? OR
                        last_checked < NOW() - INTERVAL ? MICROSECOND";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array(
                $this->attemptID,
                self::ATTEMPT_EXPIRATION_TIMEOUT * 1000
            ));
        }

        return $this->isLoginValid;
    }

    /**
     * Calls the callback function when the login attempt is ready, passing along the
     * result of the validation as the first parameter.
     *
     * @param callable|string $callback
     * @param int $checkTimer Delay between checks, in milliseconds.
     */
    public function whenReady($callback, $checkTimer=250)
    {
        while (!$this->isReady()) {
            usleep($checkTimer * 1000);
        }

        if (is_callable($callback)) {
            call_user_func($callback, $this->isValid());
        }
    }

    public function saveLoginAttempToLog($validLogin, $userId = "", $existUser = false, $locked = false) {
        $description = "";        
        if ($existUser) {
            $description = ($validLogin) ? 
                "Prijava uporabnika z uporabniškim imenom {$this->username} je uspela" :
                "Prijava uporabnika z uporabniškim imenom {$this->username} ni uspela: Napačno geslo";
        } else {
            $description = "Prijava ni uspela, uporabniško ime ali geslo ne obstaja";
        }
        
        if ($locked) {
            $description = "Uporabnik z uporabniškim imenom {$this->username} je zaklenjeno zaradi vnosov napačnih gesel";
        }
        
        if (!$validLogin || $locked) {
            $this->errors[] = $description;
        }
        
        $insert = [
            'username' => $this->username,
            'userid' => $userId,
            "description" => $description
        ];
        try
        {
            $this->dbConn->insert('crm_login_log', $insert);        
        }
        catch (\PDOException $pdoex) {
            $this->logger->addError($pdoex->getMessage()."\n".$pdoex->getTraceAsString());
            throw new \Exception('Dostop zavrnjen!');
            // throw new \PDOException($pdoex);
        } 
        catch (\Exception $ex) {
            $this->logger->addError($ex->getMessage()."\n".$ex->getTraceAsString());
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }

    public function setIp(Request $request) {
        $this->ipaddress = $this->clientIp->scanIps($request)[0];
    }

    public function setLockUserIfRequired($user, $validLogin) {
        $userId = $user->id;
        $failed_login_count = $user->failed_login_count;
        
        if (!$validLogin) {
            $locked = ($failed_login_count >= $this->maxInvalidLogins) ? true : false;
            if ($locked) {
                $this->dbConn->update('crm_user', array('locked' => 1, 'failed_login_count' => 0, 'updated_at' => date("Y-m-d H:i:s")), 'id', $userId);
                $this->saveLoginAttempToLog($validLogin, $userId, true, true);
            } else {
                $this->dbConn->update('crm_user', array('locked' => 0, 'failed_login_count' => $failed_login_count + 1, 'updated_at' => date("Y-m-d H:i:s")), 'id', $userId);
                $howmany = 5 - ($failed_login_count + 1);
                $this->errors[] = "Na voljo imate še {$howmany} poizkusov, potem vam bo pa uporabniško ime zaklenjeno !";
            }
        } else {
            if ($failed_login_count < $this->maxInvalidLogins) {
                $this->dbConn->update('crm_user', array('locked' => 0, 'failed_login_count' => 0, 'updated_at' => date("Y-m-d H:i:s")), 'id', $userId);
            }
        }        
    }

    public function logout() {
        $userData = $this->isUserLoggedIn();
        $session = new Session;
        $session::destroy();
        //$this->cookieManager->deleteCookie($this->cookieName, '/', COOKIEDOMAIN);
        
        $email = $userData["email"];
        $id = $userData["userid"];
        $insert = [
            'username' => $userData["email"],
            'userid' => $userData["userid"],
            "description" => 'Uporabnik se je odjavil'
        ];
        try
        {
            $this->dbConn->insert('crm_login_log', $insert);        
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
    
    function setUserCloseWindow() {
        $userData = $this->isUserLoggedIn();
        if (!empty($userData)) {
            $userId = $userData['userid'];
            $session = new Session;
            $session::destroy();
        }
    }

    /**
     * Check to see if username entered is in database and checks if password field is empty.
     *
     * @param parameters from form $postParams
     */
    function doUserHavePassword($postParams) {
        $username = $postParams['username'];
        $passUserFromDb = $this->dbConn->fetchSingleRow('crm_user', 'username', $username);
        // var_dump($passUserFromDb);
        if(!empty($passUserFromDb)) {
            if(empty($passUserFromDb->password)){
                return true;
            }
        }
        return false;
    }

}
