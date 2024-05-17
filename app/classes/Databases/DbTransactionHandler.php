<?php
namespace App\Databases;

use PDO;

/**
 * DbTransactionHandler - handler for database transaction 
 *
 * @author Boštjan Pelko Sibit d.o.o.
 */
class DbTransactionHandler {

    /**
     * Logger Interface
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     * Db connection provider instance
     * @var DbConnectionProvider
     */
    private $dbConnProv;
    
    /**
     * Active open connaction
     * @var PDO 
     */
    private $dbConnection;
    
    /**
     * If transaction established
     * @var PDO
     */
    private $transCreated = false;
    
    /**
     * Constructor 
     * @param \APP\Databases\DbConnectionProvider $dbConnProv
     * @param \Monolog\Logger $logger
     * @throws \Exception
     */
    public function __construct(DbConnectionProvider $dbConnProv, $logger) {
        $this->logger = $logger;
        try
        {
            if ($dbConnProv == null) {
                throw new \Exception("DbConnectionProvider is not provide");
            }
            if ($logger == null) {
                throw new \Exception("Logger interface is not provide");
            }
            
            $this->dbConnProv = $dbConnProv;
            $this->logger = $logger;
            $this->setDbConnectionOpened();
        }
        catch(\PDOException $pdoex)
        {
            $this->logger->info(__CLASS__." error: ".$pdoex);
            // throw new \Exception($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        }
        catch(\Exception $ex)
        {
            $this->logger->info(__CLASS__." error: ".$ex);
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }        
    }
    
    /**
     * Open database connection and bind to context
     * @throws \Exception is connection is not open
     */
    private function setDbConnectionOpened() {
        try
        {
            if (!$this->dbConnection) {
                $this->dbConnection = $this->dbConnProv->openConnection();
            }            
        }
        catch(\PDOException $pdoex)
        {
            $this->logger->info(__CLASS__." error: ".$pdoex);
            // throw new \Exception($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        }
        catch(\Exception $ex)
        {
            $this->logger->info(__CLASS__." error: ".$ex);
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
    }
    
    public function getDbConnectionProvider() {
        return $this->dbConnProv;
    }
            
    /**
     * Check if databse connection is open
     * @return boolean
     */
    private function hasActiveConnection() {
        if (!empty($this->dbConnection)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Cteate transaction
     * @throws \Exception
     */
    public function start() {
        try
        {
            if (!$this->hasActiveConnection()) {
                return;
            }
            if (($this->transCreated == false) && ($this->dbConnection->beginTransaction())) {
                $this->transCreated = true;
            } else {
                $this->transCreated = false;
            }
        }
        catch(\PDOException $pdoex)
        {
            $this->logger->info(__CLASS__." error: ".$pdoex);
            // throw new \Exception($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        }
        catch(\Exception $ex)
        {
            $this->logger->info(__CLASS__." error: ".$ex);
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }        
    }
    
    /**
     * Commit active transaction
     * @throws \Exception
     */
    public function commit() {
        try
        {
            if (($this->transCreated) && ($this->dbConnection->commit())) {
                $this->transCreated = false;
            }
        }
        catch(\PDOException $pdoex)
        {
            $this->logger->info(__CLASS__." error: ".$pdoex);
            $this->transCreated = false;
            // throw new \Exception($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        }
        catch(\Exception $ex)
        {
            $this->logger->info(__CLASS__." error: ".$ex);
            $this->transCreated = false;
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
        finally {
            $this->transCreated = false;
            $this->dbConnProv->closeConnection();
        }
    }
        
    /**
     * Rollback active transaction
     * @throws \Exception
     */
    public function rollback() {
        try
        {
            if (($this->transCreated) && ($this->dbConnection->rollBack())) {
                $this->transCreated = false;
            }
        }
        catch(\PDOException $pdoex)
        {
            $this->logger->info(__CLASS__." error: ".$pdoex);
            $this->transCreated = false;
            // throw new \Exception($pdoex);
            throw new \Exception('Dostop zavrnjen!');
        }
        catch(\Exception $ex)
        {
            $this->logger->info(__CLASS__." error: ".$ex);
            $this->transCreated = false;
            // throw new \Exception($ex);
            throw new \Exception('Dostop zavrnjen!');
        }
        finally {
            $this->transCreated = false;
            $this->dbConnProv->closeConnection();
        }
    }
}
