<?php

use Phalcon\Mvc\Router\Group as RouterGroup;

class UserRoutes extends RouterGroup {
    
    public function initialize() {

        $this->setPaths(
            [
                'controller'    => 'user'
            ]
        );

        $this->setPrefix('/api');

        $this->addGet(
            '/list',
            [
                'action' => 'list'
            ]
        );
    }

}