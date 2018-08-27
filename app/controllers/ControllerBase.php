<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class ControllerBase extends Controller
{
    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        $this->view->disable();
    }

    public function sendResponse($data = null) {
        if (!is_null($data)) {
             echo json_encode($data);
             return;
        }
        echo json_encode(array());
    }
}
