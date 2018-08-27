<?php

class IndexController extends ControllerBase
{

    public function indexAction() {
    }

    public function testAction() {
        $this->sendResponse(array('test' => 200));
    }

    public function route404Action() {
        echo json_encode(array('not_found' => 404));
    }

}

