<?php

use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Http\Response;

class ControllerBase extends Controller
{   
    /**
     * Función que se ejecuta ANTES de ingresar al método del Controlador.
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher) {
        $authorizeException = (array)$this->config->authorizeException;
        if (!in_array($this->router->getMatchedRoute()->getName(), $authorizeException)) {
            if (!$this->isAuthorize()) {
                $this->view->disable();
                $this->response =  $this->sendResponseHttp(array(
                    'status' => 'error',
                    'message' => 'Access denied'
                ), false, 401, 'Unauthorized');
                $this->response->setContentType('application/json');                
                $this->response->send();
                return false;
            }
        }
    }

    /**
     * Función que se ejecutar DESPUÉS de salir del método del Controlador.
     */
    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        $this->view->disable();
    }

    /**
     * Función para obtener e inicializar las variables de la paginación
     */
    public function getDataPaginator() {
        $limit  = $this->request->getQuery('limit', 'int', 20);
        if ($limit <= 0) {
            $limit = 20;
        }
        $page   = $this->request->getQuery('page', 'int', 1);
        if ($page <= 0) {
            $page = 1;
        }
        $this->limit = $limit;
        $this->page = $page;
    }

    /**
     * Función para brindar una respuesta en formato JSON
     */
    public function sendResponse($data = null) {
        if (!is_null($data)) {
             return json_encode($data);
        }
        return json_encode(array());
    }

    /**
     * Función para brindar una respuesta en formato JSON utilizado un objeto HTTP.
     */
    public function sendResponseHttp($data = null, $errors = false, $code = 200, $status = 'Ok' ) {
        $response = new Response();
        if (!is_null($data) && !$errors) {
            $response->setStatusCode($code, $status);
            $response->setJsonContent(
                [
                    'status' => $status,
                    'data' => $data
                ]
            );
        } else if (!is_null($data) && $errors) {
            $response->setStatusCode($code, $status);
            $response->setJsonContent(
                [
                    'status' => $status,
                    'messages' => $data
                ]
            );
        } else {
            $response->setStatusCode(204, 'Not Content');
        }
        return $response;
    }

    public function interpretMessages($messages = null) {
        if (is_null($messages)) {
            return array();
        } else {
            $_messages = array();

            foreach ($messages as $key => $message) {
                switch ($message->getType()) {
                    case 'InvalidCreateAttempt':
                        $messages[] = new Message(
                            'El registro no puede ser creado porque ya existe',
                            $message->getField(),
                            $message->getType()
                        );
                        unset($messages[$key]);
                        break;
    
                    case 'InvalidUpdateAttempt':
                        $messages[] = new Message(
                            'El registro no puede ser actualizado porque no existe',
                            $message->getField(),
                            $message->getType()
                        );
                        unset($messages[$key]);
                        break;
    
                    case 'PresenceOf':
                        $messages[] = new Message(
                            'El campo ' . $message->getField() . ' es obligatorio',
                            $message->getField(),
                            $message->getType()
                        );
                        unset($messages[$key]);
                        break;
                }
            }
            
            foreach ($messages as $message) {
                $_messages[] = [
                    'message'   => $message->getMessage(),
                    'field'     => $message->getField(),
                    'type'      => $message->getType()
                ];
            }

            return $_messages;
        }
    }

    public function isAuthorize($token = '') {
        $token = ($this->request->getHeader('Authorization') !== null) ? $this->request->getHeader('Authorization') : $token;
        if (isset($token) && strlen($token) > 0) {
            $status = $this->common->validateToken($token);
            return $status;
        }   
        return false;
    }
}
