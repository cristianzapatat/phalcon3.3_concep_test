<?php

use Phalcon\Mvc\Router\Group as RouterGroup;

class UserRoutes extends RouterGroup {
    
    public function initialize() {

        $this->setPaths(
            [
                'controller' => 'user'
            ]
        );

        $this->setPrefix('/user');

        $this->addGet(
            '/{0,1}((\?(.+))+|\?{0,1})',
            [
                'action' => 'list'
            ]
        );

        $this->addPost(
            '(/save(/{0,1})|/{0,1})',
            [
                'action' => 'save'
            ]
        );
    }

}