<?php

namespace Utilities;

use App\Model\Token;

use DateTime;
use DateInterval;

class Common {

    protected $FORMAT_TIME  = 'Y-m-d H:i:s';
    protected $INCREASE      = 'PT10M';
    protected $crypt;
    protected $config;

    public function setCrypt($_crypt = null) {
        if (!is_null($_crypt)) {
            $this->crypt = $_crypt;
        }
    }

    public function setConfig($_config = null) {
        if (!is_null($_config)) {
            if (isset($_config->common->format) && strlen($_config->common->format) > 0) {
                $this->FORMAT_TIME = $_config->common->format;
            }
            if (isset($_config->common->increase) && strlen($_config->common->increase) > 0) {
                $this->INCREASE = $_config->common->increase;
            }
            $this->config = $_config;
        }
    }
    
    public function encode($string = '') {
        $token = $this->crypt->encrypt($string);
        $token = $this->crypt->encryptBase64($token); 
        return $token;
    }

    public function decode($string = '') {
        $token = $this->crypt->decryptBase64($string);
        $token = $this->crypt->decrypt($token);
        return $token;
    }

    public function createToken($user = null, $type = '0') {
        if (!is_null($user)) {
            $tok  = $user->id .
                    $this->config->crypt->separator .
                    $user->email .
                    $this->config->crypt->separator .
                    $type;

            $tok = $this->encode($tok);

            $date   = new DateTime();

            $token = new Token();
            $token->setId($user->id);
            $token->setType($type);
            $token->setToken($tok);
            $token->setCreateAt($date->format($this->FORMAT_TIME));
            $token->setExpireAt($date->add(new DateInterval($this->INCREASE))->format($this->FORMAT_TIME));

            if ($token->save() !== false) {
                return $tok;
            }
        }
        return null;
    }

    public function updateToken($tok = '') {
        if (strlen($tok) > 0) {
            $_tok = $this->decode($tok);
            $_tok = explode($this->config->crypt->separator, $_tok);

            if (count($_tok) == 3) {
                $token = Token::findFirst(
                    [
                        'id = :id: AND type = :type:',
                        'bind' => [
                            'id'   => $_tok[0],
                            'type' => $_tok[2]
                        ]
                    ]
                );
                if ($token !== false) {
                    $date   = new DateTime();
                    $token->setExpireAt($date->add(new DateInterval($this->INCREASE))->format($this->FORMAT_TIME));
    
                    if ($token->save() !== false) {
                        return $tok;
                    }
                }
            }
        }
        return null;
    }

    public function validateToken($tok = '') {
        if (strlen($tok) > 0) {
            $_tok = $this->decode($tok);
            $_tok = explode($this->config->crypt->separator, $_tok);

            if (count($_tok) == 3) {
                $token = Token::findFirst(
                    [
                        'id = :id: AND type = :type:',
                        'bind' => [
                            'id'   => $_tok[0],
                            'type' => $_tok[2]
                        ]
                    ]
                );
                if ($token !== false) {
                    $expire = DateTime::createFromFormat($this->FORMAT_TIME, $token->getExpireAt());
                    $date   = new DateTime();

                    if ($expire->getTimestamp() >= $date->getTimestamp()) {
                        $status = $this->updateToken($tok);

                        if (!is_null($status)) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
}