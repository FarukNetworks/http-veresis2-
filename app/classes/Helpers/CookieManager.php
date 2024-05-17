<?php
namespace App\Helpers;

class CookieManager
{
    protected $_secret = '';
    protected $_algorithm = 'aes-256-cbc';
    protected $_highConfidentiality = true;
    protected $_ssl = false;

    public function __construct($secret, $config = null)
    {
        if (empty($secret)) {
            throw new \Exception('You must provide a secret key');
        }
        $this->_secret = $secret;

        if ($config !== null && !is_array($config)) {
            throw new \Exception('Config must be an array');
        }
        if (is_array($config)) {
            if (isset($config['high_confidentiality'])) {
                $this->_highConfidentiality = $config['high_confidentiality'];
            }
            if (isset($config['algorithm'])) {
                $this->_algorithm = $config['algorithm'];
            }
            if (isset($config['enable_ssl'])) {
                $this->_ssl = $config['enable_ssl'];
            }
        }
    }

    public function getHighConfidentiality()
    {
        return $this->_highConfidentiality;
    }

    public function setHighConfidentiality($enable)
    {
        $this->_highConfidentiality = $enable;
        return $this;
    }

    public function getSSL()
    {
        return $this->_ssl;
    }

    public function setSSL($enable)
    {
        $this->_ssl = $enable;
        return $this;
    }

    public function setCookie($cookiename, $value, $username, $expire = 0, $path = '', $domain = '', $secure = false, $httponly = null)
    {
        $secureValue = $this->_secureCookieValue($value, $username, $expire);
        $this->setClassicCookie($cookiename, $secureValue, $expire, $path, $domain, $secure, $httponly);
    }

    public function deleteCookie($name, $path = '/', $domain = '', $secure = false, $httponly = null)
    {
        $expire = 315554400; // 1980-01-01
        if ($this->cookieExists($name)) {
            setcookie($name, '', $expire, $path, $domain, $secure, $httponly);
        }
    }

    public function getCookieValue($cookiename, $deleteIfInvalid = true)
    {
        if ($this->cookieExists($cookiename)) {
            $cookieValues = explode('|', $_COOKIE[$cookiename]);
            if ((count($cookieValues) === 4) &&
                ($cookieValues[1] == 0 || $cookieValues[1] >= time())) {
                $key = hash_hmac('sha1', $cookieValues[0] . $cookieValues[1], $this->_secret);
                $cookieData = base64_decode($cookieValues[2]);
                if ($this->getHighConfidentiality()) {
                    $data = $this->_decrypt($cookieData, $key, md5($cookieValues[1]));
                } else {
                    $data = $cookieData;
                }

                if ($this->_ssl && isset($_SERVER['SSL_SESSION_ID'])) {
                    $verifKey = hash_hmac('sha1', $cookieValues[0] . $cookieValues[1] . $data . $_SERVER['SSL_SESSION_ID'], $key);
                } else {
                    $verifKey = hash_hmac('sha1', $cookieValues[0] . $cookieValues[1] . $data, $key);
                }

                if ($verifKey == $cookieValues[3]) {
                    return $data;
                }
            }
        }
        if ($deleteIfInvalid) {
            $this->deleteCookie($cookiename);
        }
        return false;
    }

    public function setClassicCookie($cookiename, $value, $expire = 0, $path = '', $domain = '', $secure = false, $httponly = null)
    {
        if ($httponly === null) {
            setcookie($cookiename, $value, $expire, $path, $domain, $secure);
        } else {
            setcookie($cookiename, $value, $expire, $path, $domain, $secure, $httponly);
        }
    }

    public function cookieExists($cookiename)
    {
        return isset($_COOKIE[$cookiename]);
    }

    protected function _secureCookieValue($value, $username, $expire)
    {
        $key = hash_hmac('sha1', $username . $expire, $this->_secret);
        if ($this->getHighConfidentiality()) {
            $encryptedValue = base64_encode($this->_encrypt($value, $key, md5($expire)));
        } else {
            $encryptedValue = base64_encode($value);
        }

        if ($this->_ssl && isset($_SERVER['SSL_SESSION_ID'])) {
            $verifKey = hash_hmac('sha1', $username . $expire . $value . $_SERVER['SSL_SESSION_ID'], $key);
        } else {
            $verifKey = hash_hmac('sha1', $username . $expire . $value, $key);
        }

        $result = array($username, $expire, $encryptedValue, $verifKey);
        return implode('|', $result);
    }

    protected function _encrypt($data, $key, $iv)
    {
        $iv = $this->_validateIv($iv);
        $encryptedData = openssl_encrypt($data, $this->_algorithm, $key, 0, $iv);
        return $encryptedData;
    }

    protected function _decrypt($data, $key, $iv)
    {
        $iv = $this->_validateIv($iv);
        $decryptedData = openssl_decrypt($data, $this->_algorithm, $key, 0, $iv);
        return $decryptedData;
    }

    protected function _validateIv($iv)
    {
        $ivSize = openssl_cipher_iv_length($this->_algorithm);
        if (strlen($iv) > $ivSize) {
            $iv = substr($iv, 0, $ivSize);
        }
        return $iv;
    }

    protected function _validateKey($key)
    {
        $keySize = 32; // 256 bits for aes-256-cbc
        if (strlen($key) > $keySize) {
            $key = substr($key, 0, $keySize);
        }
        return $key;
    }
}

?>