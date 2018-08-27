<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Http\Response;

class ControllerBase extends Controller
{   
    /**
     * Función que se ejecuta ANTES de ingresar al método del Controlador.
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher) {

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
        $this->páge = $page;
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
}
