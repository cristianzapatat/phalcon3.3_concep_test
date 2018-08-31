<?php

namespace Utilities;

use App\Model\Token;

use DateTime;
use DateInterval;

class Common {

    protected $crypt;
    protected $config;

    public function setCrypt($_crypt = null) {
        if (!is_null($_crypt)) {
            $this->crypt = $_crypt;
        }
    }

    public function setConfig($_config = null) {
        if (!is_null($_config)) {
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
            $token->setCreateAt($date->format('Y-m-d H:i:s'));
            $token->setExpireAt($date->add(new DateInterval('PT10M'))->format('Y-m-d H:i:s'));

            if ($token->save() !== false) {
                return $tok;
            }
        }
        return null;
    }

    public function updateToken($tok = '') {
        if (strlen($tok) > 0) {
            $_tok = $tok;
            $tok = $this->decode($tok);
            $tok = explode($this->config->crypt->separator, $tok);

            $token = Token::findFirst(
                [
                    'id = :id: AND type = :type:',
                    'bind' => [
                        'id'   => $tok[0],
                        'type' => $tok[2]
                    ]
                ]
            );
            if ($token !== false) {
                $date   = new DateTime();
                $token->setExpireAt($date->add(new DateInterval('PT10M'))->format('Y-m-d H:i:s'));

                if ($token->save() !== false) {
                    return $tok;
                }
            }
        }
        return null;
    }

    public function validateToken($token = '') {
        if (strlen($token) > 0) {
            
        }
    }
}